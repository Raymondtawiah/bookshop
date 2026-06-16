<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Nationality;
use App\Models\Order;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Process checkout with customer details.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'residence' => 'required|string|max:500',
            'nationality' => 'required|string|max:100',
            'contact' => 'required|string|max:20',
            'payment_method' => 'required|in:momo,bank',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product_price * $item->quantity;
        });

        $paymentMethod = $request->payment_method;
        $reference = 'ORD-'.time().rand(1000, 9999);

        // Prepare order items data
        $orderItems = $cartItems->map(function ($item) {
            return [
                'book_id' => $item->book_id,
                'product_name' => $item->product_name,
                'product_price' => $item->product_price,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        // Create order with pending payment status
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'residence' => $request->residence,
            'nationality' => $request->nationality,
            'contact' => $request->contact,
            'payment_method' => $paymentMethod,
            'total_amount' => $total,
            'status' => 'pending',
            'order_number' => $reference,
            'order_items' => $orderItems,
        ]);

        // Don't send email here - it will be sent after payment is confirmed in the callback
        // This ensures we only send email when payment is actually successful

        $paystack = new PaystackService;

        if ($paymentMethod === 'momo') {
            // For Mobile Money - use Paystack checkout page with mobile money
            // This redirects to Paystack where user can select mobile money as payment option
            $result = $paystack->initializePayment(
                $request->email,
                $total,
                $reference
            );

            Log::info('Paystack Payment Init Response for MoMo', ['result' => $result]);

            if ($result['success']) {
                // Don't clear cart here - it will be cleared in the payment callback
                // after payment is confirmed. The order already has order_items saved.

                // Redirect to Paystack checkout
                return redirect($result['authorization_url']);
            }

            // Log the failure for debugging
            Log::error('Paystack payment initialization failed', [
                'result' => $result,
                'message' => $result['message'] ?? 'Unknown error',
            ]);

            return back()->with('error', 'Payment failed: '.($result['message'] ?? 'Please try again.'));

        } elseif ($paymentMethod === 'bank') {
            // For Bank Transfer - show bank details from config
            $order->update([
                'payment_status' => 'pending',
                'status' => 'pending_payment',
                'order_items' => $orderItems,
            ]);

            // Clear cart
            $cartItems->each->delete();

            return view('cart.checkout', [
                'order' => $order,
                'total' => $total,
                'bankTransfer' => true,
                'bankDetails' => config('paystack.bankDetails'),
                'nationalities' => Nationality::orderBy('name')->get(),
            ]);
        }

        return back()->with('error', 'Invalid payment method selected.');
    }

    /**
     * Detect mobile network from phone number
     */
    private function detectNetwork($phoneNumber)
    {
        // Remove any spaces or dashes
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Get the first 3 digits after country code (233) or 0
        if (strlen($phone) === 12 && substr($phone, 0, 3) === '233') {
            $prefix = substr($phone, 3, 3);
        } elseif (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            $prefix = substr($phone, 1, 3);
        } else {
            $prefix = substr($phone, 0, 3);
        }

        // MTN Ghana
        if (in_array($prefix, ['540', '550', '560', '570', '580', '240', '250'])) {
            return 'MTN';
        }
        // Vodafone Ghana
        if (in_array($prefix, ['520', '530', '540', '200', '210'])) {
            return 'VODAFONE';
        }
        // AirtelTigo
        if (in_array($prefix, ['270', '280', '290', '570', '571'])) {
            return 'AIRTELTIGO';
        }

        return 'MTN'; // Default to MTN
    }

    /**
     * View user's orders.
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }
}
