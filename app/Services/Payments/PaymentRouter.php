<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Services\PaystackService;
use App\Services\StripeService;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Log;

class PaymentRouter
{
    protected PaystackService $paystack;
    protected StripeService $stripe;
    protected ExchangeRateService $exchangeRate;

    public function __construct(
        PaystackService $paystack,
        StripeService $stripe,
        ExchangeRateService $exchangeRate
    ) {
        $this->paystack = $paystack;
        $this->stripe = $stripe;
        $this->exchangeRate = $exchangeRate;
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
            $result = $this->paystack->initializePayment(
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
            $amountUsd = $this->exchangeRate->convertGhsToUsd($amountGhs);

            $successUrl = route('payment.stripe.success');
            $cancelUrl = route('checkout');

            $result = $this->stripe->createCheckoutSession(
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
                'exchange_rate' => $this->exchangeRate->getGhsToUsdRate(),
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
     * Converts amounts to GHS (base currency) for verification
     *
     * @param Order $order
     * @param float $paidAmount Amount paid in provider's currency
     * @param string $provider 'paystack' or 'stripe'
     * @param string $transactionReference Transaction ID
     * @param array $optionalData Additional data
     * @return Order
     */
    public function completeOrder(
        Order $order,
        float $paidAmount,
        string $provider,
        string $transactionReference,
        array $optionalData = []
    ): Order {
        $paidAmountGhs = $paidAmount;

        if ($provider === 'stripe') {
            $exchangeRate = $optionalData['exchange_rate'] ?? $this->exchangeRate->getGhsToUsdRate();
            $paidAmountGhs = $paidAmount * $exchangeRate;
        }

        return app(\App\Services\OrderCompletionService::class)->completeOrder(
            $order,
            $paidAmountGhs,
            $provider,
            $transactionReference
        );
    }
}