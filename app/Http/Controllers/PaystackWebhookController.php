<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('paystack.secretKey');
    }

    /**
     * Handle Paystack webhook
     */
    public function handleWebhook(Request $request)
    {
        $signature = $request->header('x-paystack-signature');

        if (! $signature) {
            Log::warning('Paystack webhook: Missing signature header');

            return response()->json(['error' => 'Missing signature'], 403);
        }

        $payload = $request->all();
        $payloadString = $request->getContent();

        $expectedSignature = hash_hmac('sha512', $payloadString, $this->secretKey);

        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('Paystack webhook: Invalid signature', [
                'received' => $signature,
                'expected' => $expectedSignature,
            ]);

            return response()->json(['error' => 'Invalid signature'], 403);
        }

        Log::info('Paystack webhook received', [
            'event' => $payload['event'] ?? 'unknown',
            'payment_reference' => $payload['data']['reference'] ?? null,
        ]);

        $event = $payload['event'] ?? null;

        if ($event === 'charge.success') {
            return $this->handleSuccessfulPayment($payload);
        }

        Log::info('Paystack webhook: Ignoring event', ['event' => $event]);

        return response()->json(['status' => 'ignored']);
    }

    /**
     * Handle successful payment
     */
    protected function handleSuccessfulPayment(array $payload): Response
    {
        $data = $payload['data'] ?? [];
        $reference = $data['reference'] ?? null;
        $amountKobo = $data['amount'] ?? 0;
        $amountGhs = $amountKobo / 100;
        $status = $data['status'] ?? null;
        $customerEmail = $data['customer']['email'] ?? null;

        if (! $reference) {
            Log::error('Paystack webhook: No reference found');

            return response()->json(['error' => 'No reference'], 400);
        }

        $order = Order::where('order_number', $reference)->first();

        if (! $order) {
            Log::error('Paystack webhook: Order not found', ['reference' => $reference]);

            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->status === 'paid') {
            Log::info('Paystack webhook: Order already paid', [
                'order_id' => $order->id,
                'reference' => $reference,
            ]);

            return response()->json(['status' => 'already_processed']);
        }

        $expectedAmountGhs = $order->total_amount;
        $tolerance = 1;

        if (abs($amountGhs - $expectedAmountGhs) > $tolerance) {
            Log::error('Paystack webhook: Amount mismatch', [
                'order_id' => $order->id,
                'expected_ghs' => $expectedAmountGhs,
                'paid_ghs' => $amountGhs,
                'reference' => $reference,
            ]);

            return response()->json(['error' => 'Amount mismatch'], 400);
        }

        if ($status !== 'success') {
            Log::error('Paystack webhook: Payment not successful', [
                'order_id' => $order->id,
                'status' => $status,
                'reference' => $reference,
            ]);

            return response()->json(['error' => 'Payment not successful'], 400);
        }

        $order->update([
            'status' => 'paid',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        Log::info('Paystack webhook: Payment confirmed', [
            'order_id' => $order->id,
            'amount_ghs' => $amountGhs,
            'reference' => $reference,
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Manual verification endpoint
     */
    public function verifyPayment(string $reference)
    {
        $order = Order::where('order_number', $reference)->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        if ($order->status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Order already paid',
                'order' => $order,
            ]);
        }

        $paystack = new PaystackService;
        $result = $paystack->verifyPayment($reference);

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed',
            ], 400);
        }

        $amountGhs = $result['amount'];
        $expectedAmountGhs = $order->total_amount;
        $tolerance = 1;

        if (abs($amountGhs - $expectedAmountGhs) > $tolerance) {
            Log::error('Manual verify: Amount mismatch', [
                'order_id' => $order->id,
                'expected_ghs' => $expectedAmountGhs,
                'paid_ghs' => $amountGhs,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment amount mismatch',
            ], 400);
        }

        $order->update([
            'status' => 'paid',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        Log::info('Manual payment verification: Success', [
            'order_id' => $order->id,
            'reference' => $reference,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully',
            'order' => $order,
        ]);
    }
}
