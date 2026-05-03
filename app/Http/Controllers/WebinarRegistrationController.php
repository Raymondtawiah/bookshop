<?php

namespace App\Http\Controllers;

use App\Models\Webinar;
use App\Models\WebinarRegistration;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebinarRegistrationController extends Controller
{
    protected $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->middleware('auth')->except(['webhook']);
        $this->paystack = $paystack;
    }

    /**
     * Register user for a webinar.
     */
    public function register(Request $request, Webinar $webinar)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        if ($webinar->registrations()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You are already registered for this webinar.');
        }

        $registration = $webinar->registrations()->create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'registration_status' => 'registered',
            'payment_status' => $webinar->price > 0 ? 'pending' : 'paid',
            'amount_paid' => $webinar->price,
        ]);

        if ($webinar->price == 0) {
            return redirect()->route('webinars.show', $webinar)
                ->with('success', 'You have been registered for this webinar successfully!');
        }

        return redirect()->route('webinars.payment', [$webinar, $registration])
            ->with('success', 'Registration successful! Please complete payment.');
    }

    /**
     * Show payment page.
     */
    public function payment(Webinar $webinar, WebinarRegistration $registration)
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
    public function initializePayment(Webinar $webinar, WebinarRegistration $registration)
    {
        $this->authorizePayment($registration);

        if ($registration->isPaid()) {
            return redirect()->route('webinars.show', $webinar)
                ->with('success', 'Payment already completed!');
        }

        $amount = $webinar->price; // PaystackService will convert to pesewas
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
            ]);

            return redirect()->route('webinars.show', $registration->webinar)
                ->with('success', 'Payment successful! You can now join the webinar.');
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
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'verification_failed'], 200);
    }

    /**
     * Join webinar page - redirects to verification.
     */
    public function join(Webinar $webinar)
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
    public function showVerification(Webinar $webinar, WebinarRegistration $registration)
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
    public function processVerification(Webinar $webinar, WebinarRegistration $registration, Request $request)
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
    public function showVerifiedJoin(Webinar $webinar, WebinarRegistration $registration)
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
     * Authorize that the user can make payment for this registration.
     */
    protected function authorizePayment(WebinarRegistration $registration)
    {
        if (Auth::id() !== $registration->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($registration->isPaid()) {
            abort(403, 'Payment already completed.');
        }
    }
}
