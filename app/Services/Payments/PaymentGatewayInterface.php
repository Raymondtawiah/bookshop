<?php

namespace App\Services\Payments;

interface PaymentGatewayInterface
{
    public function createCheckout(
        string $email,
        float $amountUsd,
        string $reference,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): array;

    public function verifyPayment(string $reference): array;

    public function getProviderName(): string;
}
