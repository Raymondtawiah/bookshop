<?php

namespace App\Http\Controllers;

use App\Mail\WebinarPaymentSuccess;
use App\Models\WebinarRegistration;
use App\Models\WebinarSession;
use App\Services\StripeService;
use App\Services\WebinarAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebinarRegistrationController extends Controller
{
    protected $stripe;

    protected $accessService;

    public function __construct(StripeService $stripe, WebinarAccessService $accessService)
    {
        $this->middleware('auth')->except(['access', 'storeRegistration', 'payment', 'initializePayment', 'paymentCallback', 'paymentSuccess']);
        $this->stripe = $stripe;
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
     * Store webinar registration (public - guests allowed).
     */
    public function storeRegistration(Request $request, WebinarSession $webinar)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required',
        ]);

        // Check if already registered (for logged-in users)
        $existingRegistration = $user
            ? $webinar->registrations()->where('user_id', $user->id)->first()
            : $webinar->registrations()->where('email', $request->email)->whereNull('user_id')->first();

        if ($existingRegistration) {
            if ($existingRegistration->isPaid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already registered and paid for this webinar. Check your email for the access link.',
                ]);
            }

            // If not paid, redirect to payment
            return response()->json([
                'success' => true,
                'redirect_url' => route('webinars.payment', [$webinar, $existingRegistration]),
            ]);
        }

        // Create registration - with user_id for logged-in users, null for guests
        $registration = $webinar->registrations()->create([
            'user_id' => $user ? $user->id : null,
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
                'message' => 'Registration successful! Check your email for details.',
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('webinars.payment', [$webinar, $registration]),
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
     * Initialize Stripe payment.
     */
    public function initializePayment(Request $request, WebinarSession $webinar, WebinarRegistration $registration)
    {
        $this->authorizePayment($registration);

        if ($registration->isPaid()) {
            return redirect()->route('webinars.show', $webinar)
                ->with('success', 'Payment already completed!');
        }

        $amount = $webinar->current_price; // Amount in USD
        $reference = 'WEB-'.$webinar->id.'-'.$registration->id.'-'.time();

        $result = $this->stripe->createCheckoutSession(
            $registration->email,
            $amount,
            $reference,
            route('webinars.payment.callback'),
            route('webinars.index'),
            [],
            $webinar->title,
            'Registration for webinar: '.$webinar->title
        );

        if ($result['success']) {
            $registration->update(['transaction_reference' => $reference]);

            return response()->json([
                'success' => true,
                'authorization_url' => $result['checkout_url'],
                'reference' => $reference,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Payment initialization failed',
        ]);
    }

    /**
     * Handle Stripe payment callback.
     */
    public function paymentCallback(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (! $sessionId) {
            return redirect()->route('webinars.index')
                ->with('error', 'Payment session not found.');
        }

        // Find registration by reference (we need to get the reference from the session)
        $stripeSession = $this->stripe->retrieveSession($sessionId);

        if (! $stripeSession) {
            return redirect()->route('webinars.index')
                ->with('error', 'Unable to retrieve payment session.');
        }

        $reference = $stripeSession->client_reference_id;

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

        $result = $this->stripe->verifyPayment($sessionId);

        if ($result['success']) {
            // Update registration with payment details FIRST
            $registration->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'transaction_reference' => $reference,
                'amount_paid' => $result['amount'] ?? $registration->amount_paid,
            ]);

            // Generate encrypted access link
            $accessLink = $this->accessService->generateAccessLink($registration);

            // Send confirmation email (non-critical - payment already confirmed)
            $this->sendPaymentConfirmation($registration);

            return redirect()->route('webinars.index')
                ->with('success', 'Payment successful! Please check your email for the webinar access link.');
        }

        return redirect()->route('webinars.payment', [$registration->webinar, $registration])
            ->with('error', 'Payment verification failed. Please try again.');
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
        if (! $registration->isPaid()) {
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
        if (! $registration->isPaid()) {
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
        if (! $registration->joined_at) {
            $registration->update([
                'joined_at' => now(),
            ]);
        }

        return view('webinars.verified-join', compact('webinar', 'registration'));
    }

    /**
     * Send webinar payment confirmation email.
     */
    protected function sendPaymentConfirmation(WebinarRegistration $registration): void
    {
        try {
            $webinar = $registration->webinar;
            // Send direct webinar link (Zoom link) - no access restrictions
            $webinarLink = $webinar->webinar_link;

            \Mail::to($registration->email)->send(
                new WebinarPaymentSuccess($registration, $webinar, $webinarLink, $webinarLink)
            );

            // Mark email as sent successfully
            $registration->update([
                'email_sent_at' => now(),
                'email_attempts' => $registration->email_attempts + 1,
            ]);

            Log::info('Webinar payment confirmation email sent', [
                'registration_id' => $registration->id,
                'webinar_id' => $webinar->id,
                'email' => $registration->email,
            ]);
        } catch (\Exception $e) {
            // Mark email as failed but don't throw exception - payment is already confirmed
            $registration->update([
                'email_failed_at' => now(),
                'email_error' => $e->getMessage(),
                'email_attempts' => $registration->email_attempts + 1,
            ]);

            Log::error('Failed to send webinar payment confirmation email', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show webinar access via encrypted link
     */
    public function access(Request $request, WebinarSession $webinar, string $token)
    {
        $registration = $this->accessService->canAccessWebinar($token, $webinar->id);

        if (! $registration) {
            abort(403, 'Your access link has expired. Please register again to access this webinar.');
        }

        // Mark as joined if not already
        if (! $registration->joined_at) {
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
