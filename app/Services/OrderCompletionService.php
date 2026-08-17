<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderCompletionService
{
    public function completeOrder(
        Order $order,
        float $paidAmount,
        string $paymentProvider,
        string $transactionReference,
        string $paymentStatus = 'paid'
    ): Order {
        Log::info('OrderCompletion: Starting order completion', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'provider' => $paymentProvider,
            'reference' => $transactionReference,
            'paid_amount' => $paidAmount,
        ]);

        if ($order->status === 'paid') {
            Log::warning('OrderCompletion: Order already completed', [
                'order_id' => $order->id,
            ]);

            return $order;
        }

        $expectedAmount = $order->total_amount;
        $isPaystack = $paymentProvider === 'paystack';
        $tolerance = $isPaystack ? 2.0 : max(0.01, $expectedAmount * 0.5);

        if ($isPaystack) {
            if (abs($paidAmount - $expectedAmount) > $tolerance) {
                Log::warning('OrderCompletion: Paystack amount mismatch, but proceeding', [
                    'order_id' => $order->id,
                    'expected_ghs' => $expectedAmount,
                    'paid_ghs' => $paidAmount,
                ]);
            }
        } else {
            if (abs($paidAmount - $expectedAmount) > $tolerance) {
                Log::error('OrderCompletion: Amount mismatch', [
                    'order_id' => $order->id,
                    'expected' => $expectedAmount,
                    'paid' => $paidAmount,
                    'tolerance' => $tolerance,
                ]);
                throw new \Exception('Payment amount mismatch: expected '.$expectedAmount.', got '.$paidAmount);
            }
        }

        if ($paidAmount > $expectedAmount) {
            Log::warning('OrderCompletion: Paid amount higher than expected, using expected amount', [
                'order_id' => $order->id,
                'expected' => $expectedAmount,
                'paid' => $paidAmount,
            ]);
            $paidAmount = $expectedAmount;
        }

        DB::beginTransaction();

        try {
            $orderItems = $order->order_items ?? collect([]);

            $order->update([
                'status' => 'paid',
                'payment_status' => $paymentStatus,
                'paid_at' => now(),
                'payment_provider' => $paymentProvider,
                'transaction_reference' => $transactionReference,
                'order_items' => $orderItems->toArray(),
            ]);

            $this->sendOrderConfirmationEmail($order);

            NotificationService::newOrder($order);
            NotificationService::paymentReceived($order);

            DB::commit();

            Log::info('OrderCompletion: Order completed successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderCompletion: Failed to complete order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function verifyAndCompleteOrder(
        string $reference,
        float $paidAmount,
        string $paymentProvider,
        string $transactionReference,
        array $optionalData = []
    ): Order {
        Log::info('OrderCompletion: Verifying and completing order', [
            'reference' => $reference,
            'provider' => $paymentProvider,
        ]);

        $order = Order::where('order_number', $reference)->first();

        if (! $order) {
            Log::error('OrderCompletion: Order not found', ['reference' => $reference]);
            throw new \Exception('Order not found');
        }

        if ($order->status === 'paid') {
            Log::info('OrderCompletion: Order already paid', ['order_id' => $order->id]);
            return $order;
        }

        return $this->completeOrder($order, $paidAmount, $paymentProvider, $transactionReference);
    }

    protected function sendOrderConfirmationEmail(Order $order, $cartItems = null, ?string $overrideEmail = null): void
    {
        try {
            $recipientEmail = $overrideEmail ?? $order->email;

            if (! $cartItems) {
                $cartItems = collect($order->order_items ?? []);
            }

            $usdAmount = $order->total_amount_usd ?? $order->total_amount;

            Mail::to($recipientEmail)->send(new OrderConfirmation($order, $cartItems, $usdAmount));

            Log::info('Order confirmation email sent', [
                'order_id' => $order->id,
                'recipient' => $recipientEmail,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'recipient' => $overrideEmail ?? $order->email,
            ]);
        }
    }
}
