<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Services\OrderCompletionService;
use Illuminate\Support\Facades\Log;

class PaymentRouterService
{
    protected array $gateways = [];

    protected OrderCompletionService $orderCompletion;

    public function __construct()
    {
        $this->gateways['stripe'] = app(StripePaymentGateway::class);
        $this->gateways['paystack'] = app(PaystackPaymentGateway::class);
        $this->orderCompletion = app(OrderCompletionService::class);
    }

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

        if (! isset($this->gateways[$provider])) {
            return [
                'success' => false,
                'message' => 'Unsupported payment provider: '.$provider,
            ];
        }

        $successUrl = route('payment.success');
        $cancelUrl = route('home');

        return $this->gateways[$provider]->createCheckout(
            $email,
            $amountUsd,
            $reference,
            $successUrl,
            $cancelUrl,
            $metadata
        );
    }

    public function routePayment(
        string $email,
        float $amountUsd,
        string $reference,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): array {
        $amountUsd = max($amountUsd, 0.01);

        Log::info('Routing payment', [
            'amount_usd' => $amountUsd,
            'reference' => $reference,
            'email' => $email,
        ]);

        $stripeGateway = $this->gateways['stripe'] ?? null;
        if ($stripeGateway) {
            $result = $stripeGateway->createCheckout(
                $email,
                $amountUsd,
                $reference,
                $successUrl,
                $cancelUrl,
                $metadata
            );

            if ($result['success']) {
                return $result;
            }
        }

        return [
            'success' => false,
            'message' => 'Payment initialization failed',
        ];
    }

    public function completeOrder(Order $order, float $paidAmount, string $provider, string $transactionReference): Order
    {
        Log::info('PaymentRouter: Completing order', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'provider' => $provider,
            'amount' => $paidAmount,
            'transaction_id' => $transactionReference,
        ]);

        return $this->orderCompletion->completeOrder(
            $order,
            $paidAmount,
            $provider,
            $transactionReference
        );
    }
}
