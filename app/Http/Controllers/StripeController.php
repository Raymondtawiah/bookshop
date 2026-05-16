<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ExchangeRateService;
use App\Services\OrderCompletionService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeController extends Controller
{
    protected StripeService $stripe;
    protected OrderCompletionService $orderCompletion;
    protected ExchangeRateService $exchangeRate;

    public function __construct(
        StripeService $stripe,
        OrderCompletionService $orderCompletion,
        ExchangeRateService $exchangeRate
    ) {
        $this->stripe = $stripe;
        $this->orderCompletion = $orderCompletion;
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * Handle successful Stripe payment redirect.
     * URL: /payment/stripe/success?session_id={CHECKOUT_SESSION_ID}
     */
    public function success(Request $request, ?string $sessionId = null)
    {
        $sessionId = $sessionId ?? $request->query('session_id');

        if (!$sessionId) {
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
        // Retrieve order and show success if already completed.
        $order = Order::where('order_number', $session->client_reference_id)->first();

        if ($order && $order->status === 'paid') {
            return view('cart.checkout', [
                'order' => $order,
                'total' => $order->total_amount,
            ]);
        }

        // If webhook hasn't fired yet, we can complete order here as fallback
        if ($order && $order->status !== 'paid') {
            try {
                $reference = $session->client_reference_id;
                $amountUsd = $session->amount_total / 100; // cents to USD
                $exchangeRate = $order->exchange_rate ?? $this->exchangeRate->getGhsToUsdRate();
                $paidAmountGhs = $amountUsd * $exchangeRate;
                $transactionId = $session->payment_intent ?? $session->id;

                $this->orderCompletion->completeOrder(
                    $order,
                    $paidAmountGhs,
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
                return redirect()->route('checkout')
                    ->with('error', 'Payment verified but order processing failed. Please contact support.');
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
     * Handle Stripe webhook.
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
     * Handle checkout.session.completed event
     */
    protected function handleCheckoutSessionCompleted($session): void
    {
        $reference = $session->client_reference_id ?? null;
        $paymentStatus = $session->payment_status ?? null;
        $amountCents = $session->amount_total ?? 0;
        $amountUsd = $amountCents / 100; // Convert cents to USD
        $transactionId = $session->payment_intent ?? $session->id;

        if (!$reference) {
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

        try {
            $order = Order::where('order_number', $reference)->first();

            if (!$order) {
                Log::error('Stripe webhook: Order not found', ['reference' => $reference]);
                return;
            }

            // Convert USD to GHS for verification
            $exchangeRate = $order->exchange_rate ?? $this->exchangeRate->getGhsToUsdRate();
            $paidAmountGhs = $amountUsd * $exchangeRate;

            $this->orderCompletion->completeOrder(
                $order,
                $paidAmountGhs,
                'stripe',
                $transactionId
            );

            Log::info('Stripe webhook: Payment confirmed', [
                'order_id' => $order->id,
                'amount_usd' => $amountUsd,
                'amount_ghs' => $paidAmountGhs,
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
     * Handle payment_intent.succeeded event
     */
    protected function handlePaymentIntentSucceeded($paymentIntent): void
    {
        $reference = $paymentIntent->metadata->client_reference_id ?? null;
        $amountCents = $paymentIntent->amount ?? 0;
        $amountUsd = $amountCents / 100;
        $transactionId = $paymentIntent->id;

        if (!$reference) {
            Log::error('Stripe webhook: No reference in payment intent');
            return;
        }

        try {
            $order = Order::where('order_number', $reference)->first();

            if (!$order) {
                Log::error('Stripe webhook: Order not found', ['reference' => $reference]);
                return;
            }

            $exchangeRate = $order->exchange_rate ?? $this->exchangeRate->getGhsToUsdRate();
            $paidAmountGhs = $amountUsd * $exchangeRate;

            $this->orderCompletion->completeOrder(
                $order,
                $paidAmountGhs,
                'stripe',
                $transactionId,
                ['payment_intent_id' => $paymentIntent->id]
            );
        } catch (\Exception $e) {
            Log::error('Stripe webhook: Payment intent completion failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
        }
    }
}