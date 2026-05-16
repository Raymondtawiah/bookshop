<?php

namespace App\Http\Controllers;

use App\Services\Payments\PaymentRouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentRouterService $paymentRouter;

    public function __construct(PaymentRouterService $paymentRouter)
    {
        $this->paymentRouter = $paymentRouter;
    }

    /**
     * Initialize payment (AJAX endpoint)
     *
     * Expected POST fields:
     * - payment_method: 'momo' | 'card' | 'bank'
     * - email (optional, uses authenticated user if omitted)
     * - customer_name, residence, contact, nationality
     */
    public function initializePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:momo,card,bank',
            'email' => 'nullable|email',
            'contact' => 'required|string',
            'customer_name' => 'required|string',
            'residence' => 'required|string',
            'nationality' => 'nullable|string',
        ]);

        // Get cart
        $cartItems = \App\Models\Cart::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty',
            ]);
        }

        $totalGhs = $cartItems->sum(fn($item) => $item->unit_price * $item->quantity);
        $email = $request->input('email') ?? (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->email : null);

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email is required'], 400);
        }

        $reference = 'ORD-'.time().rand(1000, 9999);
        $paymentMethod = $request->payment_method;

         // Bank Transfer: handle without redirect
         if ($paymentMethod === 'bank') {
             $this->createPendingOrder($request, $totalGhs, $reference, [
                 'provider' => 'bank',
                 'currency' => 'GHS',
             ]);

             return response()->json([
                 'success' => true,
                 'type' => 'bank_transfer',
                 'reference' => $reference,
             ]);
         }

        // Determine provider based on selected payment method
        $paymentMethod = $request->payment_method;
        switch ($paymentMethod) {
            case 'momo':
                $provider = 'paystack';
                break;
            case 'card':
                $provider = 'stripe';
                break;
            case 'bank':
                // Bank transfer is handled separately above
                break;
            default:
                $provider = 'paystack'; // default to paystack
                break;
        }

        $paymentResult = $this->paymentRouter->createCheckout(
            $email,
            $totalGhs,
            $provider,
            $reference
        );

        if ($paymentResult['success']) {
            $this->createPendingOrder($request, $totalGhs, $reference, $paymentResult);

            return response()->json([
                'success' => true,
                'checkout_url' => $paymentResult['url'],
                'provider' => $paymentResult['provider'],
                'currency' => $paymentResult['currency'],
                'reference' => $reference,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $paymentResult['message'] ?? 'Payment initialization failed',
        ], 400);
    }

    /**
     * Handle payment callback redirect
     */
    public function callback(Request $request)
    {
        // Check if it's a Stripe redirect (has session_id)
        if ($request->has('session_id')) {
            $sessionId = $request->input('session_id');
            $stripe = app(\App\Services\StripeService::class);
            $session = $stripe->retrieveSession($sessionId);

            if (!$session) {
                return redirect()->route('checkout')->with('error', 'Invalid session');
            }

            $reference = $session->client_reference_id;
            $provider = 'stripe';

            // Verify the payment
            $result = $stripe->verifyPayment($sessionId);

            if (!$result['success']) {
                return redirect()->route('checkout')->with('error', 'Payment verification failed. Please contact support.');
            }

        } else {
            // Paystack redirect (has reference)
            $reference = $request->input('reference');
            if (!$reference) {
                return redirect()->route('checkout')->with('error', 'Payment reference not found');
            }

            $provider = 'paystack';
            $paystack = app(\App\Services\PaystackService::class);
            $result = $paystack->verifyPayment($reference);

            if (!$result['success']) {
                return redirect()->route('checkout')->with('error', 'Payment verification failed. Please contact support.');
            }
        }

        // Now we have $reference, $provider, and $result (which is the verification result)
        $order = \App\Models\Order::where('order_number', $reference)->first();

        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Order not found.');
        }

        try {
            // For Paystack, the amount in $result is in GHS (as per PaystackService)
            // For Stripe, the amount in $result is in USD (as per StripeService)
            $this->paymentRouter->completeOrder(
                $order,
                $result['amount'],
                $provider,
                $provider === 'stripe' ? $result['session_id'] : $result['reference']
            );

            return redirect()->route('home')
                ->with('success', 'Payment successful! Order confirmed. Check your email for details.');
        } catch (\Exception $e) {
            Log::error('Payment callback: Order completion failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('checkout')
                ->with('error', 'Payment verified but order processing failed. Please contact support.');
        }
    }

    /**
     * Check payment status (polling endpoint for MoMo)
     */
    public function checkPaymentStatus(Request $request)
    {
        $reference = $request->reference;
        $paystack = app(\App\Services\PaystackService::class);
        $result = $paystack->verifyPayment($reference);

        if ($result['success']) {
            $order = \App\Models\Order::where('order_number', $reference)->first();
            if ($order && $order->status === 'paid') {
                return response()->json(['success' => true, 'message' => 'Payment confirmed']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Payment not yet confirmed']);
    }

    /**
     * Get banks list
     */
    public function getBanks()
    {
        $paystack = app(\App\Services\PaystackService::class);
        $result = $paystack->getBanks();

        return $result['status']
            ? response()->json(['success' => true, 'banks' => $result['data']])
            : response()->json(['success' => false, 'message' => 'Failed to fetch banks']);
    }

    /**
     * Create pending order (shared method)
     *
     * @param Request $request
     * @param float $totalGhs
     * @param string $reference
     * @param array $paymentResult Router result or bank transfer info
     * @return \App\Models\Order
     */
    protected function createPendingOrder(Request $request, float $totalGhs, string $reference, array $paymentResult): \App\Models\Order
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $cartItems = \App\Models\Cart::where('user_id', $user->id)->get();

        $orderItems = $cartItems->map(fn($item) => [
            'book_id' => $item->book_id,
            'product_name' => $item->product_name,
            'unit_price_ghs' => $item->unit_price,
            'quantity' => $item->quantity,
            'total_price_ghs' => $item->unit_price * $item->quantity,
        ])->toArray();

        return \App\Models\Order::create([
            'user_id' => $user->id,
            'order_number' => $reference,
            'customer_name' => $request->customer_name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'contact' => $request->contact ?? $user->phone ?? '',
            'residence' => $request->residence ?? '',
            'nationality' => $request->nationality ?? '',
            'payment_method' => $this->mapProviderToMethod($paymentResult['provider']),
            'payment_provider' => $paymentResult['provider'],
            'total_amount' => $totalGhs,
            'currency' => $paymentResult['currency'],
            'total_amount_usd' => $paymentResult['amount_usd'] ?? null,
            'exchange_rate' => $paymentResult['exchange_rate'] ?? null,
            'status' => 'pending',
            'payment_status' => 'pending',
            'order_items' => $orderItems,
        ]);
    }

    /**
     * Map provider to payment_method value
     */
    private function mapProviderToMethod(string $provider): string
    {
        return match ($provider) {
            'paystack' => 'momo',
            'stripe' => 'card',
            'bank' => 'bank',
            default => 'momo',
        };
    }
}