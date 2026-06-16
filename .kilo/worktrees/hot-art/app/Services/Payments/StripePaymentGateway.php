<?php

namespace App\Services\Payments;

use App\Services\StripeService;
use Illuminate\Support\Facades\Log;

class StripePaymentGateway implements PaymentGatewayInterface
{
    protected StripeService $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function createCheckout(
        string $email,
        float $amountUsd,
        string $reference,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): array {
        $amountUsd = max($amountUsd, 0.01);

        try {
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
                'reference' => $result['reference'],
            ];
        } catch (\Exception $e) {
            Log::error('StripePaymentGateway: Checkout failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $sessionId): array
    {
        return $this->stripe->verifyPayment($sessionId);
    }

    public function getProviderName(): string
    {
        return 'stripe';
    }
}
