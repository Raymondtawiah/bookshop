<?php

namespace App\Http\Controllers;

use App\Models\WebinarSession;
use App\Models\WebinarRegistration;
use App\Services\PaystackService;
use App\Services\WebinarAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebinarRegistrationController extends Controller
{
    protected $paystack;
    protected $accessService;

    public function __construct(PaystackService $paystack, WebinarAccessService $accessService)
    {
        $this->middleware('auth')->except(['webhook', 'access', 'storeRegistration', 'payment', 'initializePayment', 'paymentCallback', 'paymentSuccess']);
        $this->paystack = $paystack;
        $this->accessService = $accessService;
    }

    /**
     * Show registration form for webinar.
     */
    public function register(Request $request, WebinarSession $webinar)
    {
        $user = Auth::user();

        // Check if already registered
        if ($webinar->registrations()->where('user_id', $user->id)->exists()) {
            return redirect()->route('webinars.show', $webinar)
                ->with('error', 'You are already registered for this webinar.');
        }

        return view('webinars.register', compact('webinar'));
    }

    /**
     * Store webinar registration (guests allowed).
     */
    public function storeRegistration(Request $request, WebinarSession $webinar)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required',
        ]);

        // Check if payments are currently open (Sunday-Thursday only)
        if (! $webinar->arePaymentsOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration is currently closed. Registration is open every Sunday to Thursday and closes every Thursday early. Registration reopens next Sunday.'
            ]);
        }

        // Check if already registered with this email
        $existingRegistration = $webinar->registrations()
            ->where('email', $request->email)
            ->first();

        if ($existingRegistration) {
            if ($existingRegistration->isPaid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already registered and paid for this webinar. Check your email for the access link.'
                ]);
            }
            // If not paid, redirect to payment
            return response()->json([
                'success' => true,
                'redirect_url' => route('webinars.payment', [$webinar, $existingRegistration])
            ]);
        }

        // Create registration without user_id (guest registration)
        $registration = $webinar->registrations()->create([
            'user_id' => null, // Guest registration
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'registration_status' => 'registered',
            'payment_status' => $webinar->current_price > 0 ? 'pending' : 'paid',
            'amount_paid' => $webinar->current_price,
        ]);

        if ($webinar->current_price == 0) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Check your email for details.'
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('webinars.payment', [$webinar, $registration])
        ]);
    }

    /**
     * Show payment page.
     */
    public function payment(Request $request, WebinarSession $webinar, WebinarRegistration $registration)
    {
        $this->authorizePayment($registration);

        if ($registration->isPaid()) {
            return redirect()->route('webinars.show', $webinar)
                ->with('success', 'Payment already completed!');
        }

        return view('webinars.payment', compact('webinar', 'registration'));
    }

    /**
     * Initialize Paystack payment.
     */
    public function initializePayment(Request $request, WebinarSession $webinar, WebinarRegistration $registration)
    {
        $this->authorizePayment($registration);

        if ($registration->isPaid()) {
            return redirect()->route('webinars.show', $webinar)
                ->with('success', 'Payment already completed!');
        }

        $amount = $webinar->current_price; // PaystackService will convert to pesewas
        $reference = 'WEB-'.$webinar->id.'-'.$registration->id.'-'.time();

        $result = $this->paystack->initializePayment(
            $registration->email,
            $amount,
            $reference,
            'GHS',
            route('webinars.payment.callback')
        );

        if ($result['success']) {
            $registration->update(['transaction_reference' => $reference]);

            return response()->json([
                'success' => true,
                'authorization_url' => $result['authorization_url'],
                'reference' => $reference,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Payment initialization failed',
        ]);
    }

    /**
     * Handle Paystack payment callback.
     */
    public function paymentCallback(Request $request)
    {
        $reference = $request->get('reference');

        if (! $reference) {
            return redirect()->route('webinars.index')
                ->with('error', 'Payment reference not found.');
        }

        // Find registration by reference
        $registration = WebinarRegistration::where('transaction_reference', $reference)->first();

        if (! $registration) {
            return redirect()->route('webinars.index')
                ->with('error', 'Registration not found.');
        }

        $result = $this->paystack->verifyPayment($reference);

        if ($result['success']) {
            $registration->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'transaction_reference' => $reference,
                'amount_paid' => $result['amount'] ?? $registration->amount_paid,
            ]);

            // Generate encrypted access link
            $accessLink = $this->accessService->generateAccessLink($registration);

            return redirect()->route('webinars.success', [$registration->webinar, $registration])
                ->with('success', 'Payment successful! Your webinar access link has been generated.')
                ->with('access_link', $accessLink);
        }

        return redirect()->route('webinars.payment', [$registration->webinar, $registration])
            ->with('error', 'Payment verification failed. Please try again.');
    }

    /**
     * Handle Paystack webhook for payment verification.
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        if (! hash_equals($payload['event'], 'charge.success')) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $reference = $payload['data']['reference'] ?? null;
        $email = $payload['data']['customer']['email'] ?? null;

        if (! $reference) {
            return response()->json(['status' => 'error', 'message' => 'No reference'], 200);
        }

        $registration = WebinarRegistration::where('transaction_reference', $reference)->first();

        if (! $registration) {
            return response()->json(['status' => 'error', 'message' => 'Registration not found'], 200);
        }

        if ($registration->isPaid()) {
            return response()->json(['status' => 'already_paid'], 200);
        }

        $result = $this->paystack->verifyPayment($reference);

        if ($result['success']) {
            $registration->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'amount_paid' => $result['amount'] ?? $registration->amount_paid,
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'verification_failed'], 200);
    }

    /**
     * Join webinar page - redirects to verification.
     */
    public function join(WebinarSession $webinar)
    {
        $user = Auth::user();

        $registration = $webinar->registrations()->where('user_id', $user->id)->firstOrFail();

        if (! $registration->isPaid()) {
            abort(403, 'You must complete payment before joining the webinar.');
        }

        // Redirect to verification page
        return redirect()->route('webinars.verify.join', [$webinar, $registration]);
    }

    
    /**
     * Show verification page before joining webinar.
     */
    public function showVerification(WebinarSession $webinar, WebinarRegistration $registration)
    {
        $user = Auth::user();

        // Verify this registration belongs to the user
        if ($registration->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Verify payment is completed
        if (!$registration->isPaid()) {
            return redirect()->route('webinars.payment', [$webinar, $registration])
                ->with('error', 'Please complete payment before joining.');
        }

        // Access token should exist (generated by join method)
        // If it doesn't exist or is expired, we'll handle it gracefully

        return view('webinars.verify', compact('webinar', 'registration'));
    }

    /**
     * Process verification and show actual join link.
     */
    public function processVerification(Request $request, WebinarSession $webinar, WebinarRegistration $registration)
    {
        $user = Auth::user();

        // Simple validation
        $request->validate([
            'terms_agreed' => 'required|accepted',
        ], [
            'terms_agreed.accepted' => 'You must agree to the terms to join the webinar.',
        ]);

        // Verify this registration belongs to the user
        if ($registration->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Verify payment is completed
        if (!$registration->isPaid()) {
            return redirect()->route('webinars.payment', [$webinar, $registration])
                ->with('error', 'Please complete payment before joining.');
        }

        // Simple update - mark as joined
        $registration->update([
            'joined_at' => now(),
        ]);

        // Direct redirect to verified join page
        return redirect()->route('webinars.join.verified', [$webinar, $registration])
            ->with('success', 'Welcome! You can now join the webinar.');
    }

    /**
     * Show verified join page with actual Zoom link.
     */
    public function showVerifiedJoin(WebinarSession $webinar, WebinarRegistration $registration)
    {
        // Mark as joined if not already set
        if (!$registration->joined_at) {
            $registration->update([
                'joined_at' => now(),
            ]);
        }

        return view('webinars.verified-join', compact('webinar', 'registration'));
    }

    /**
     * Show success page with access link after payment (guests allowed)
     */
    public function paymentSuccess(WebinarSession $webinar, WebinarRegistration $registration)
    {
        // Verify payment is completed
        if (!$registration->isPaid()) {
            return redirect()->route('webinars.payment', [$webinar, $registration])
                ->with('error', 'Please complete payment first.');
        }

        // Generate or refresh access link
        $accessLink = $this->accessService->generateAccessLink($registration);

        return view('webinars.success', compact('webinar', 'registration', 'accessLink'));
    }

    /**
     * Handle webinar access via encrypted link
     */
    public function access(Request $request, WebinarSession $webinar, string $token)
    {
        $registration = $this->accessService->canAccessWebinar($token, $webinar->id);

        if (!$registration) {
            abort(403, 'Your access link has expired. Please register again to access this webinar.');
        }

        // Mark as joined if not already
        if (!$registration->joined_at) {
            $registration->update(['joined_at' => now()]);
        }

        return view('webinars.access', compact('webinar', 'registration'));
    }

    /**
     * Authorize that the user can make payment for this registration.
     * Allows guest registrations (user_id is null).
     */
    protected function authorizePayment(WebinarRegistration $registration)
    {
        // Allow guest registrations (user_id is null)
        // For logged-in users, verify ownership
        if (Auth::check() && Auth::id() !== $registration->user_id && $registration->user_id !== null) {
            abort(403, 'Unauthorized action.');
        }

        if ($registration->isPaid()) {
            abort(403, 'Payment already completed.');
        }
    }
}
