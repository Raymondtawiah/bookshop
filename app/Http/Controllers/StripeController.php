<?php

namespace App\Http\Controllers;

use App\Mail\WebinarPaymentSuccess;
use App\Models\Order;
use App\Models\WebinarRegistration;
use App\Services\OrderCompletionService;
use App\Services\StripeService;
use App\Services\WebinarAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeController extends Controller
{
    protected StripeService $stripe;

    protected OrderCompletionService $orderCompletion;

    protected WebinarAccessService $webinarAccess;

    public function __construct(
        StripeService $stripe,
        OrderCompletionService $orderCompletion,
        WebinarAccessService $webinarAccess
    ) {
        $this->stripe = $stripe;
        $this->orderCompletion = $orderCompletion;
        $this->webinarAccess = $webinarAccess;
    }

    /**
     * Handle successful Stripe payment redirect.
     * URL: /payment/stripe/success?session_id={CHECKOUT_SESSION_ID}
     */
    public function success(Request $request, ?string $sessionId = null)
    {
        $sessionId = $sessionId ?? $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('checkout')
                ->with('error', 'Invalid payment session.');
        }

        try {
            $session = $this->stripe->retrieveSession($sessionId);
        } catch (\Exception $e) {
            Log::error('Stripe session retrieval failed', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('checkout')
                ->with('error', 'Unable to verify payment. Please contact support.');
        }

        // Payment may not be completed yet; show a pending page
        if ($session->payment_status !== 'paid') {
            return view('cart.checkout', [
                'paymentPending' => true,
                'paymentMessage' => 'Your payment is being processed. You will receive a confirmation shortly.',
                'order' => Order::where('order_number', $session->client_reference_id)->first(),
            ]);
        }

        // Payment is confirmed; the webhook should have already completed the order.
        // Retrieve order and redirect to dashboard if already completed.
        $order = Order::where('order_number', $session->client_reference_id)->first();

        if ($order && $order->status === 'paid') {
            return redirect()->route('home')
                ->with('success', 'Payment successful! Order confirmed.');
        }

        // If webhook hasn't fired yet, we can complete order here as fallback
        if ($order && $order->status !== 'paid') {
            try {
                $reference = $session->client_reference_id;
                $amountUsd = $session->amount_total / 100; // cents to USD
                $transactionId = $session->payment_intent ?? $session->id;

                $this->orderCompletion->completeOrder(
                    $order,
                    $amountUsd,
                    'stripe',
                    $transactionId
                );

                return redirect()->route('home')
                    ->with('success', 'Payment successful! Order confirmed.');
            } catch (\Exception $e) {
                Log::error('Stripe order completion failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);

                // Provide more specific error message based on exception type
                $errorMessage = 'Payment verified but order processing failed. Please contact support.';
                if (strpos($e->getMessage(), 'sendOrderConfirmationEmail') !== false) {
                    $errorMessage = 'Error sending confirmation email. Please contact support.';
                } elseif (strpos($e->getMessage(), 'NotificationService') !== false) {
                    $errorMessage = 'Error sending notifications. Please contact support.';
                } elseif (strpos($e->getMessage(), 'Cart::where') !== false) {
                    $errorMessage = 'Error clearing cart after payment. Please contact support.';
                } elseif (strpos($e->getMessage(), 'SQLSTATE') !== false || strpos($e->getMessage(), 'query') !== false) {
                    $errorMessage = 'Database error during order completion. Please contact support.';
                } else {
                    // Show actual error message for debugging (remove in production)
                    $errorMessage = 'Payment failed: '.$e->getMessage();
                }

                return redirect()->route('checkout')
                    ->with('error', $errorMessage);
            }
        }

        return redirect()->route('home')
            ->with('success', 'Payment successful!');
    }

    /**
     * Handle cancelled Stripe payment.
     * URL: /payment/stripe/cancel
     */
    public function cancel(Request $request)
    {
        return redirect()->route('checkout')
            ->with('error', 'Payment was cancelled.');
    }

    /**
     * Handle Stripe webhook - unified endpoint for all Stripe events.
     * URL: /webhook/stripe
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');
        $endpointSecret = config('stripe.webhookSecret');

        if (empty($endpointSecret)) {
            Log::warning('Stripe webhook secret not configured');

            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Webhook error'], 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type]);

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;

            default:
                Log::info('Stripe webhook: unhandled event type', ['type' => $event->type]);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle checkout.session.completed event - unified for orders and webinars
     */
    protected function handleCheckoutSessionCompleted($session): void
    {
        $reference = $session->client_reference_id ?? null;
        $paymentStatus = $session->payment_status ?? null;
        $amountCents = $session->amount_total ?? 0;
        $amountUsd = $amountCents / 100; // Convert cents to USD
        $transactionId = $session->payment_intent ?? $session->id;

        if (! $reference) {
            Log::error('Stripe webhook: No reference found in session');

            return;
        }

        if ($paymentStatus !== 'paid') {
            Log::info('Stripe webhook: Session not paid', [
                'reference' => $reference,
                'status' => $paymentStatus,
            ]);

            return;
        }

        // Check if this is a webinar registration (starts with WEB-)
        if (str_starts_with($reference, 'WEB-')) {
            $this->handleWebinarPayment($session, $reference, $amountUsd);

            return;
        }

        // Handle regular order
        try {
            $order = Order::where('order_number', $reference)->first();

            if (! $order) {
                Log::error('Stripe webhook: Order not found', ['reference' => $reference]);

                return;
            }

            $this->orderCompletion->completeOrder(
                $order,
                $amountUsd,
                'stripe',
                $transactionId
            );

            Log::info('Stripe webhook: Payment confirmed', [
                'order_id' => $order->id,
                'amount_usd' => $amountUsd,
                'reference' => $reference,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe webhook: Order completion failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle webinar payment - separated from order handling
     */
    protected function handleWebinarPayment($session, string $reference, float $amountUsd): void
    {
        $registration = WebinarRegistration::where('transaction_reference', $reference)->first();

        if (! $registration) {
            Log::error('Stripe webhook: Webinar registration not found', ['reference' => $reference]);

            return;
        }

        if ($registration->payment_status === 'paid') {
            Log::info('Stripe webhook: Webinar already paid', ['reference' => $reference]);

            return;
        }

        try {
            $registration->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'amount_paid' => $amountUsd,
            ]);

            // Generate access link
            $accessLink = $this->webinarAccess->generateAccessLink($registration);

            // Send confirmation email
            Mail::to($registration->email)->send(
                new WebinarPaymentSuccess($registration, $registration->webinar, $accessLink)
            );

            Log::info('Stripe webhook: Webinar payment confirmed', [
                'registration_id' => $registration->id,
                'webinar_id' => $registration->webinar_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe webhook: Webinar payment handling failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle payment_intent.succeeded event
     */
    protected function handlePaymentIntentSucceeded($paymentIntent): void
    {
        $reference = $paymentIntent->metadata->client_reference_id ?? null;
        $amountCents = $paymentIntent->amount ?? 0;
        $amountUsd = $amountCents / 100;
        $transactionId = $paymentIntent->id;

        if (! $reference) {
            Log::error('Stripe webhook: No reference in payment intent');

            return;
        }

        // Check if this is a webinar registration (starts with WEB-)
        if (str_starts_with($reference, 'WEB-')) {
            $registration = WebinarRegistration::where('transaction_reference', $reference)->first();

            if (! $registration) {
                Log::error('Stripe webhook: Webinar registration not found', ['reference' => $reference]);

                return;
            }

            if ($registration->payment_status === 'paid') {
                return;
            }

            try {
                $registration->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'amount_paid' => $amountUsd,
                ]);
            } catch (\Exception $e) {
                Log::error('Stripe webhook: Webinar payment intent completion failed', [
                    'reference' => $reference,
                    'error' => $e->getMessage(),
                ]);
            }

            return;
        }

        try {
            $order = Order::where('order_number', $reference)->first();

            if (! $order) {
                Log::error('Stripe webhook: Order not found', ['reference' => $reference]);

                return;
            }

            $this->orderCompletion->completeOrder(
                $order,
                $amountUsd,
                'stripe',
                $transactionId
            );
        } catch (\Exception $e) {
            Log::error('Stripe webhook: Payment intent completion failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
