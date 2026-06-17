<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Services\OrderCompletionService;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;

class PaymentRouter
{
    protected StripeService $stripe;

    public function __construct(
        StripeService $stripe
    ) {
        $this->stripe = $stripe;
    }

    /**
     * Create checkout for given provider
     *
     * @param  string  $email  Customer email
     * @param  float  $amountUsd  Amount in USD (prices are already in USD)
     * @param  string  $provider  'stripe'
     * @param  string  $reference  Unique order reference
     * @param  array  $metadata  Additional metadata
     */
    public function createCheckout(
        string $email,
        float $amountUsd,
        string $provider,
        string $reference,
        array $metadata = []
    ): array {
        Log::info('PaymentRouter: Creating checkout', [
            'provider' => $provider,
            'amount_usd' => $amountUsd,
        ]);

        if ($provider === 'stripe') {
            // Amount is already in USD, no conversion needed
            // Ensure we have at least 1 cent (minimum for Stripe)
            $amountUsd = max($amountUsd, 0.01);

            $successUrl = route('payment.stripe.success');
            $cancelUrl = route('checkout');

            $result = $this->stripe->createCheckoutSession(
                $email,
                $amountUsd,
                $reference,
                $successUrl,
                $cancelUrl,
                $metadata
            );

            if (! $result['success']) {
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
                'amount_usd' => $amountUsd,
                'reference' => $reference,
            ];
        }

        return [
            'success' => false,
            'message' => 'Unsupported payment provider: '.$provider,
        ];
    }

    /**
     * Complete order with provider-specific verification
     *
     * @param  float  $paidAmount  Amount paid in USD
     * @param  string  $provider  'stripe'
     * @param  string  $transactionReference  Transaction ID
     * @param  array  $optionalData  Additional data
     */
    public function completeOrder(
        Order $order,
        float $paidAmount,
        string $provider,
        string $transactionReference,
        array $optionalData = []
    ): Order {
        // Amount is already in USD, no conversion needed
        return app(OrderCompletionService::class)->completeOrder(
            $order,
            $paidAmount,
            $provider,
            $transactionReference
        );
    }
}
