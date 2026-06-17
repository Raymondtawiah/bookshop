<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Services\Payments\PaymentRouterService;
use App\Services\Payments\PaystackPaymentGateway;
use App\Services\Payments\StripePaymentGateway;
use App\Services\PaystackService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentRouterService $paymentRouter;

    protected StripePaymentGateway $stripeGateway;

    protected PaystackPaymentGateway $paystackGateway;

    public function __construct(
        PaymentRouterService $paymentRouter,
        StripePaymentGateway $stripeGateway,
        PaystackPaymentGateway $paystackGateway
    ) {
        $this->paymentRouter = $paymentRouter;
        $this->stripeGateway = $stripeGateway;
        $this->paystackGateway = $paystackGateway;
    }

    public function initializePayment(Request $request)
    {
        try {
            $request->validate([
                'payment_method' => 'required|in:card,paystack,bank',
                'email' => 'nullable|email',
                'contact' => 'required|string',
                'customer_name' => 'required|string',
                'residence' => 'required|string',
                'nationality' => 'nullable|string',
                'discount_code' => 'nullable|string|max:50',
            ]);

            $cartItems = Cart::where('user_id', Auth::id())->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty',
                ]);
            }

            $totalUsd = $cartItems->sum(fn ($item) => $item->unit_price * $item->quantity);
            $email = $request->input('email') ?? (Auth::check() ? Auth::user()->email : null);
            $discountCode = $request->input('discount_code');
            $discountAmount = 0;

            if ($discountCode) {
                $discount = Discount::findByCode($discountCode);
                if ($discount && $discount->type === 'ebook') {
                    $discountAmount = $discount->calculateDiscount($totalUsd);
                }
            }

            $finalAmount = max(0, $totalUsd - $discountAmount);

            if (! $email) {
                return response()->json(['success' => false, 'message' => 'Email is required'], 400);
            }

            $reference = 'ORD-'.time().rand(1000, 9999);
            $paymentMethod = $request->payment_method;

            if ($paymentMethod === 'bank') {
                $this->createPendingOrder($request, $finalAmount, $reference, [
                    'provider' => 'bank',
                    'currency' => 'USD',
                    'discount_code' => $discountCode,
                    'discount_amount' => $discountAmount,
                ]);

                return response()->json([
                    'success' => true,
                    'type' => 'bank_transfer',
                    'reference' => $reference,
                ]);
            }

            $provider = $paymentMethod === 'paystack' ? 'paystack' : 'stripe';

            $paymentResult = $this->paymentRouter->createCheckout(
                $email,
                $finalAmount,
                $provider,
                $reference
            );

            if ($paymentResult['success']) {
                $this->createPendingOrder($request, $finalAmount, $reference, $paymentResult);

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
        } catch (\Exception $e) {
            Log::error('Payment initialization exception', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed: '.$e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        if ($request->has('session_id')) {
            $sessionId = $request->input('session_id');
            $stripe = app(StripeService::class);
            $session = $stripe->retrieveSession($sessionId);

            if (! $session) {
                return redirect()->route('home')->with('error', 'Invalid session');
            }

            $reference = $session->client_reference_id;
            $provider = 'stripe';
            $result = $stripe->verifyPayment($sessionId);

            if (! $result['success']) {
                return redirect()->route('home')->with('error', 'Payment verification failed. Please contact support.');
            }

            $paidAmount = $result['amount'];
        } else {
            $reference = $request->input('reference') ?? $request->input('trxref');
            if (! $reference) {
                return redirect()->route('home')->with('error', 'Payment reference not found');
            }

            $provider = 'paystack';
            $result = $this->paystackGateway->verifyPayment($reference);

            if (! $result['success']) {
                return redirect()->route('home')->with('error', 'Payment verification failed. Please contact support.');
            }

            $paidAmount = $result['amount_ghs'] ?? $result['amount'];
        }

        $order = Order::where('order_number', $reference)->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        try {
            $this->paymentRouter->completeOrder(
                $order,
                $paidAmount,
                $provider,
                $result['reference'] ?? $result['session_id'] ?? $reference
            );

            return redirect()->route('home')
                ->with('success', 'Payment successful! Order confirmed. Check your email for details.');
        } catch (\Exception $e) {
            Log::error('Payment callback: Order completion failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            $errorMessage = 'Payment verified but order processing failed. Please contact support.';

            return redirect()->route('home')
                ->with('error', $errorMessage);
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        $reference = $request->reference;
        $result = $this->paystackGateway->verifyPayment($reference);

        if ($result['success']) {
            $order = Order::where('order_number', $reference)->first();
            if ($order && $order->status === 'paid') {
                return response()->json(['success' => true, 'message' => 'Payment confirmed']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Payment not yet confirmed']);
    }

    public function getBanks()
    {
        $paystack = app(PaystackService::class);
        $result = $paystack->getBanks();

        return $result['status']
            ? response()->json(['success' => true, 'banks' => $result['data']])
            : response()->json(['success' => false, 'message' => 'Failed to fetch banks']);
    }

    protected function createPendingOrder(Request $request, float $totalUsd, string $reference, array $paymentResult): Order
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        $orderItems = $cartItems->map(fn ($item) => [
            'book_id' => $item->book_id,
            'product_name' => $item->product_name,
            'unit_price_usd' => $item->unit_price,
            'quantity' => $item->quantity,
            'total_price_usd' => $item->unit_price * $item->quantity,
        ])->toArray();

        $isPaystack = $paymentResult['provider'] === 'paystack';
        $totalAmount = $isPaystack ? $paymentResult['amount_ghs'] : $totalUsd;

        return Order::create([
            'user_id' => $user->id,
            'order_number' => $reference,
            'customer_name' => $request->customer_name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'contact' => $request->contact ?? $user->phone ?? '',
            'residence' => $request->residence ?? '',
            'nationality' => $request->nationality ?? '',
            'payment_method' => $this->mapProviderToMethod($paymentResult['provider']),
            'payment_provider' => $paymentResult['provider'],
            'total_amount' => $totalAmount,
            'total_amount_usd' => $totalUsd,
            'currency' => $isPaystack ? 'GHS' : 'USD',
            'exchange_rate' => $isPaystack ? 11.79 : null,
            'status' => 'pending',
            'payment_status' => 'pending',
            'order_items' => $orderItems,
            'discount_code' => $paymentResult['discount_code'] ?? null,
            'discount_amount' => $paymentResult['discount_amount'] ?? 0,
        ]);
    }

    private function mapProviderToMethod(string $provider): string
    {
        return match ($provider) {
            'stripe' => 'card',
            'paystack' => 'paystack',
            'bank' => 'bank',
            default => 'card',
        };
    }
}
