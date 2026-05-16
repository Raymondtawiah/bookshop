<?php

namespace App\Services\Payments;

use App\Services\PaystackService;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;

class PaymentRouterService
{
    protected PaystackService $paystackService;
    protected StripeService $stripeService;
    protected float $conversionRate;

    public function __construct(
        PaystackService $paystackService,
        StripeService $stripeService
    ) {
        $this->paystackService = $paystackService;
        $this->stripeService = $stripeService;
        // Conversion rate: 1 GHS = X USD (configurable)
        $this->conversionRate = config('payment.stripe_ghs_to_usd_rate', 0.07); // Default 1 GHS = 0.07 USD
    }

    /**
     * Create checkout for given provider
     *
     * @param string $email Customer email
     * @param float $amountGhs Amount in GHS (base currency)
     * @param string $provider 'paystack' or 'stripe'
     * @param string $reference Unique order reference
     * @param array $metadata Additional metadata
     * @return array
     */
    public function createCheckout(
        string $email,
        float $amountGhs,
        string $provider,
        string $reference,
        array $metadata = []
    ): array {
        Log::info('PaymentRouter: Creating checkout', [
            'provider' => $provider,
            'amount_ghs' => $amountGhs,
        ]);

        if ($provider === 'paystack') {
            $result = $this->paystackService->initializePayment(
                $email,
                $amountGhs,
                $reference,
                'GHS'
            );

            if (!$result['success']) {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Paystack initialization failed',
                ];
            }

            return [
                'success' => true,
                'url' => $result['authorization_url'],
                'provider' => 'paystack',
                'currency' => 'GHS',
                'amount' => $amountGhs,
                'reference' => $reference,
            ];
        }

        if ($provider === 'stripe') {
            // For Stripe, we need to convert GHS to USD
            // The nationality should be in metadata or we need to determine it differently
            // For now, we'll assume the metadata contains nationality info or we'll need to pass it differently
            $amountUsd = $amountGhs * $this->conversionRate;
            // Ensure we have at least 1 cent (minimum for Stripe)
            $amountUsd = max($amountUsd, 0.01);

            $successUrl = route('payment.stripe.success');
            $cancelUrl = route('checkout');

            $result = $this->stripeService->createCheckoutSession(
                $email,
                $amountUsd,
                $reference,
                $successUrl,
                $cancelUrl,
                array_merge($metadata, [
                    'amount_ghs' => $amountGhs,
                ])
            );

            if (!$result['success']) {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Stripe initialization failed',
                ];
            }

            return [
                'success' => true,
                'url' => $result['checkout_url'],
                'provider' => 'stripe',
                'currency' => 'USD',
                'amount_ghs' => $amountGhs,
                'amount_usd' => $amountUsd,
                'exchange_rate' => $this->conversionRate,
                'reference' => $reference,
            ];
        }

        return [
            'success' => false,
            'message' => 'Unsupported payment provider: '.$provider,
        ];
    }

    /**
     * Route payment to appropriate service based on country
     *
     * @param string $email Customer email
     * @param float $amountGhs Amount in Ghana Cedis (GHS)
     * @param string $reference Unique order reference
     * @param string $country Country code or name (e.g., 'Ghana' or 'GH')
     * @param string $successUrl URL to redirect after successful payment
     * @param string $cancelUrl URL to redirect if payment is cancelled
     * @param array $metadata Additional metadata
     * @return array Payment response with redirect URL and reference
     */
    public function routePayment(
        string $email,
        float $amountGhs,
        string $reference,
        string $country,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): array {
        // Normalize country comparison
        $isGhana = strtolower($country) === 'ghana' || strtoupper($country) === 'GH';

        if ($isGhana) {
            // Use Paystack for Ghana users (amount in GHS)
            Log::info('Routing payment to Paystack for Ghana user', [
                'amount_ghs' => $amountGhs,
                'reference' => $reference,
                'email' => $email
            ]);

            $paystackResult = $this->paystackService->initializePayment(
                $email,
                $amountGhs,
                $reference,
                'GHS', // Currency for Paystack
                $successUrl
            );

            if (!$paystackResult['success']) {
                return $paystackResult;
            }

            return [
                'success' => true,
                'url' => $paystackResult['authorization_url'],
                'provider' => 'paystack',
                'currency' => 'GHS',
                'amount' => $amountGhs,
                'reference' => $paystackResult['reference'],
            ];
        } else {
            // Convert GHS to USD for international users
            $amountUsd = $amountGhs * $this->conversionRate;
            // Ensure we have at least 1 cent (minimum for Stripe)
            $amountUsd = max($amountUsd, 0.01);

            Log::info('Routing payment to Stripe for international user', [
                'amount_ghs' => $amountGhs,
                'amount_usd' => $amountUsd,
                'reference' => $reference,
                'email' => $email,
                'conversion_rate' => $this->conversionRate
            ]);

            $stripeResult = $this->stripeService->createCheckoutSession(
                $email,
                $amountUsd,
                $reference,
                $successUrl,
                $cancelUrl,
                $metadata
            );

            if (!$stripeResult['success']) {
                return $stripeResult;
            }

            return [
                'success' => true,
                'url' => $stripeResult['checkout_url'],
                'provider' => 'stripe',
                'currency' => 'USD',
                'amount_ghs' => $amountGhs,
                'amount_usd' => $amountUsd,
                'exchange_rate' => $this->conversionRate,
                'reference' => $stripeResult['reference'],
            ];
        }
    }

    /**
     * Get conversion rate for debugging/config purposes
     */
    public function getConversionRate(): float
    {
        return $this->conversionRate;
    }

    /**
     * Complete order after successful payment verification
     *
     * @param \App\Models\Order $order The order to complete
     * @param float $amount Payment amount
     * @param string $provider Payment provider ('paystack' or 'stripe')
     * @param string $transactionId Transaction reference or session ID
     * @return void
     */
    public function completeOrder(\App\Models\Order $order, float $amount, string $provider, string $transactionId): void
    {
        Log::info('PaymentRouter: Completing order', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'provider' => $provider,
            'amount' => $amount,
            'transaction_id' => $transactionId,
        ]);

        // Update order status
        $order->update([
            'status' => 'paid',
            'payment_status' => 'completed',
            'transaction_id' => $transactionId,
        ]);

        // Clear the user's cart
        \App\Models\Cart::where('user_id', $order->user_id)->delete();

        // Send order confirmation email
        try {
            \Illuminate\Support\Facades\Mail::to($order->email)->send(
                new \App\Mail\OrderConfirmationMail($order)
            );
        } catch (\Exception $e) {
            Log::error('PaymentRouter: Failed to send confirmation email', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}