<?php

namespace App\Services;

use App\Models\Order;
use App\Services\Payments\PaystackPaymentGateway;
use App\Services\Payments\StripePaymentGateway;
use Illuminate\Support\Facades\Log;

class PaymentRouter
{
    protected StripePaymentGateway $stripeGateway;

    protected PaystackPaymentGateway $paystackGateway;

    public function __construct(
        StripePaymentGateway $stripeGateway,
        PaystackPaymentGateway $paystackGateway
    ) {
        $this->stripeGateway = $stripeGateway;
        $this->paystackGateway = $paystackGateway;
    }

    public function createCheckout(
        string $email,
        float $amountUsd,
        string $provider,
        string $reference,
        array $metadata = [],
        ?string $productName = null,
        ?string $productDescription = null
    ): array {
        Log::info('PaymentRouter: Creating checkout', [
            'provider' => $provider,
            'amount_usd' => $amountUsd,
        ]);

        $successUrl = route('payment.success');
        $cancelUrl = route('home');

        $gateway = $provider === 'paystack' ? $this->paystackGateway : $this->stripeGateway;
        $providerName = $provider === 'paystack' ? 'paystack' : 'stripe';

        return $gateway->createCheckout(
            $email,
            $amountUsd,
            $reference,
            $successUrl,
            $cancelUrl,
            $metadata
        );
    }

    public function completeOrder(
        Order $order,
        float $paidAmount,
        string $provider,
        string $transactionReference,
        array $optionalData = []
    ): Order {
        Log::info('PaymentRouter: Completing order', [
            'order_id' => $order->id,
            'amount' => $paidAmount,
            'provider' => $provider,
        ]);

        return app(OrderCompletionService::class)->completeOrder(
            $order,
            $paidAmount,
            $provider,
            $transactionReference
        );
    }
}
