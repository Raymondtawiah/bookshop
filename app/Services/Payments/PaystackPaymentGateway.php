<?php

namespace App\Services\Payments;

use App\Services\PaystackService;
use Illuminate\Support\Facades\Log;

class PaystackPaymentGateway implements PaymentGatewayInterface
{
    protected PaystackService $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    public function createCheckout(
        string $email,
        float $amountUsd,
        string $reference,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): array {
        $amountGhs = $this->convertToGhs($amountUsd);

        try {
            $result = $this->paystack->initializePayment(
                $email,
                $amountGhs,
                $reference,
                'GHS',
                $successUrl
            );

            if (! $result['success']) {
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
                'amount_usd' => $amountUsd,
                'amount_ghs' => $amountGhs,
                'reference' => $result['reference'],
            ];
        } catch (\Exception $e) {
            Log::error('PaystackPaymentGateway: Checkout failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $reference): array
    {
        $result = $this->paystack->verifyPayment($reference);

        if ($result['success']) {
            return [
                'success' => true,
                'status' => $result['status'],
                'reference' => $result['reference'],
                'amount' => $result['amount'],
                'currency' => $result['currency'],
                'provider' => 'paystack',
            ];
        }

        return [
            'success' => false,
            'message' => $result['message'] ?? 'Paystack verification failed',
        ];
    }

    public function getProviderName(): string
    {
        return 'paystack';
    }

    private function convertToGhs(float $amountUsd): float
    {
        return round($amountUsd * 11.65, 2);
    }
}
