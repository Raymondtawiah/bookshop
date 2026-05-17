<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderCompletionService
{
    /**
     * Complete an order after successful payment.
     *
     * This is the single centralized function that finalizes an order.
     * Both Paystack and Stripe webhooks/callbacks should call this.
     *
     * @param Order $order The order to complete
     * @param float $paidAmount The amount actually paid (in GHS)
     * @param string $paymentProvider The payment provider used ('paystack' or 'stripe')
     * @param string $transactionReference Transaction reference from payment provider
     * @param string $paymentStatus Optional payment status (default: 'completed')
     * @return Order The updated order
     */
    public function completeOrder(
        Order $order,
        float $paidAmount,
        string $paymentProvider,
        string $transactionReference,
        string $paymentStatus = 'completed'
    ): Order {
        Log::info('OrderCompletion: Starting order completion', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'provider' => $paymentProvider,
            'reference' => $transactionReference,
        ]);

        // Double-check order is not already paid to prevent duplicate processing
        if ($order->status === 'paid' && $order->payment_status === 'completed') {
            Log::warning('OrderCompletion: Order already completed', [
                'order_id' => $order->id,
            ]);
            return $order;
        }

        // Validate amount (allow tolerance for Stripe minimum adjustments)
        $expectedAmount = $order->total_amount;
        $tolerance = max(0.01, $expectedAmount * 0.5); // Allow up to 50% tolerance for Stripe minimum adjustments
        if (abs($paidAmount - $expectedAmount) > $tolerance) {
            Log::error('OrderCompletion: Amount mismatch', [
                'order_id' => $order->id,
                'expected' => $expectedAmount,
                'paid' => $paidAmount,
                'tolerance' => $tolerance,
            ]);
            throw new \Exception('Payment amount mismatch: expected ' . $expectedAmount . ', got ' . $paidAmount);
        }

        // If paid amount is higher than expected but within tolerance, log it and use expected amount
        if ($paidAmount > $expectedAmount) {
            Log::warning('OrderCompletion: Paid amount higher than expected, using expected amount', [
                'order_id' => $order->id,
                'expected' => $expectedAmount,
                'paid' => $paidAmount,
            ]);
            $paidAmount = $expectedAmount;
        }

        
        // Begin transaction to ensure data consistency
        DB::beginTransaction();

         try {
             // Get cart items before clearing (for email)
             $cartItems = Cart::where('user_id', $order->user_id)->get();
 
             // Prepare order items from cart if not already set
             if ($order->order_items->isEmpty()) {
                 $orderItems = $cartItems->map(function ($item) {
                     return [
                         'book_id' => $item->book_id,
                         'product_name' => $item->product_name,
                         'unit_price_ghs' => $item->unit_price,
                         'quantity' => $item->quantity,
                         'total_price_ghs' => $item->unit_price * $item->quantity,
                     ];
                 })->toArray();
             } else {
                 $orderItems = $order->order_items->toArray();
             }
 
             // Update order details
             $order->update([
                 'status' => 'paid',
                 'payment_status' => $paymentStatus,
                 'paid_at' => now(),
                 'payment_provider' => $paymentProvider,
                 'transaction_reference' => $transactionReference,
                 'order_items' => $orderItems,
             ]);
 
             // Clear the cart only if it has items
             if (!$cartItems->isEmpty()) {
                 Cart::where('user_id', $order->user_id)->delete();
             }
 
             // Send confirmation email
             $this->sendOrderConfirmationEmail($order, $cartItems);
 
             // Send admin notifications
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

    /**
     * Verify and complete an order from a payment provider's webhook/callback.
     * This method handles verification and then calls completeOrder.
     *
     * @param string $reference Order number or session reference
     * @param float $paidAmount Amount received (in GHS)
     * @param string $paymentProvider Provider name
     * @param string $transactionReference Transaction ID from provider
     * @param array $optionalData Additional provider-specific data
     * @return Order The completed order
     */
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

        // Find the order by order_number (reference)
        $order = Order::where('order_number', $reference)->first();

        if (!$order) {
            Log::error('OrderCompletion: Order not found', ['reference' => $reference]);
            throw new \Exception('Order not found');
        }

        if ($order->status === 'paid') {
            Log::info('OrderCompletion: Order already paid', ['order_id' => $order->id]);
            return $order;
        }

        return $this->completeOrder($order, $paidAmount, $paymentProvider, $transactionReference);
    }

    /**
     * Send order confirmation email to customer.
     */
    protected function sendOrderConfirmationEmail(Order $order, $cartItems = null): void
    {
        try {
            // If cartItems not provided, get from cart
            if (!$cartItems) {
                $cartItems = Cart::where('user_id', $order->user_id)->get();
            }

            // If still empty, use order_items
            if (!$cartItems || $cartItems->isEmpty()) {
                $cartItems = collect($order->order_items ?? []);
            }

            Mail::to($order->email)->send(new OrderConfirmation($order, $cartItems, $order->total_amount));

            Log::info('Order confirmation email sent', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
        }
    }
}