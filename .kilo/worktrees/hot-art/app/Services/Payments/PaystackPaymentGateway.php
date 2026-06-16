<?php

namespace App\Services\Payments;

use App\Services\PaystackService;
use Illuminate\Support\Facades\Log;

class PaystackPaymentGateway implements PaymentGatewayInterface
{
    protected PaystackService $paystack;

    protected float $usdToGhsRate;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
        $this->usdToGhsRate = 11.79;
    }

    public function createCheckout(
        string $email,
        float $amountUsd,
        string $reference,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): array {
        try {
            $amountGhs = round($amountUsd * $this->usdToGhsRate, 2);

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

        if ($result['success'] ?? false) {
            $amountGhs = $result['amount'];
            $amountUsd = round($amountGhs / $this->usdToGhsRate, 2);

            return [
                'success' => true,
                'amount' => $amountGhs,
                'amount_usd' => $amountUsd,
                'amount_ghs' => $amountGhs,
                'currency' => 'GHS',
                'status' => $result['status'],
                'reference' => $result['reference'],
            ];
        }

        return [
            'success' => false,
            'message' => $result['message'] ?? 'Payment verification failed',
        ];
    }

    public function getProviderName(): string
    {
        return 'paystack';
    }
}
