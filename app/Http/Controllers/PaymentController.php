<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    /**
     * Initialize payment for the order
     */
    public function initializePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:momo,bank,card',
            'contact' => 'required|string',
            'customer_name' => 'required|string',
            'residence' => 'required|string',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ]);
        }

        $total = $cartItems->sum(function($item) {
            return $item->product_price * $item->quantity;
        });

        $email = $request->email ?? Auth::user()->email;
        
        // Generate unique reference
        $reference = 'ORD-' . time() . rand(1000, 9999);

        // For mobile money - redirect to Paystack checkout where user can select mobile money
        if ($request->payment_method === 'momo') {
            Log::info('Processing mobile money payment');
        }

        // For all payment methods, use Paystack checkout page
        // This allows user to select their preferred payment method (card, mobile money, bank)
        $result = $this->paystack->initializePayment($email, $total, $reference, 'GHS');

        if ($result['success']) {
            // Create pending order
            $order = $this->createPendingOrder($request, $total, $reference);

            return response()->json([
                'success' => true,
                'authorization_url' => $result['authorization_url'],
                'reference' => $reference
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Payment initialization failed'
        ]);
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
     * Handle Paystack callback
     */
    public function callback(Request $request)
    {
        $reference = $request->reference;
        
        if (!$reference) {
            return redirect()->route('checkout')
                ->with('error', 'Payment reference not found');
        }

        // Verify the payment
        $result = $this->paystack->verifyPayment($reference);

        if ($result['success']) {
            // Find and update the order
            $order = Order::where('order_number', $reference)->first();
            
            if ($order) {
                // Get cart items before clearing
                $cartItems = Cart::where('user_id', Auth::id())->get();

                // Send confirmation email to customer
                try {
                    \Log::info('Attempting to send order confirmation email', [
                        'order_id' => $order->id,
                        'email' => $order->email
                    ]);
                    
                    \Mail::to($order->email)->send(new \App\Mail\OrderConfirmation($order, $cartItems, $order->total_amount));
                    
                    \Log::info('Order confirmation email sent successfully', ['order_id' => $order->id]);
                } catch (\Exception $e) {
                    // Log email error but don't fail the order
                    \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                }

                // Clear cart
                Cart::where('user_id', Auth::id())->delete();

                $order->update([
                    'status' => 'paid',
                    'payment_status' => 'completed',
                    'paid_at' => now()
                ]);

                return redirect()->route('dashboard')
                    ->with('success', 'Payment successful! Order confirmed. Check your email for details.');
            }
        }

        return redirect()->route('checkout')
            ->with('error', 'Payment verification failed. Please contact support.');
    }

    /**
     * Check payment status (for mobile money polling)
     */
    public function checkPaymentStatus(Request $request)
    {
        $reference = $request->reference;
        
        $result = $this->paystack->verifyPayment($reference);
        
        if ($result['success']) {
            $order = Order::where('order_number', $reference)->first();
            
            if ($order && $order->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed'
                ]);
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Payment not yet confirmed'
        ]);
    }

    /**
     * Create pending order
     */
    protected function createPendingOrder(Request $request, $total, $reference)
    {
        $user = Auth::user();
        
        $cartItems = Cart::where('user_id', $user->id)->get();
        
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $reference,
            'customer_name' => $user->name,
            'email' => $user->email,
            'contact' => $request->contact ?? $user->phone ?? '',
            'residence' => $request->residence ?? '',
            'nationality' => $request->nationality ?? '',
            'payment_method' => $request->payment_method,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        // Store order items in order_items table if it exists
        // Or keep them in cart (which we'll clear after payment)
        
        return $order;
    }

    /**
     * Send order confirmation email
     */
    protected function sendOrderConfirmationEmail($order, $cartItems = null)
    {
        try {
            $user = $order->user;
            
            // If cartItems not provided, try to get from cart
            if (!$cartItems) {
                $cartItems = Cart::where('user_id', $order->user_id)->get();
            }
            
            // If still no cart items, create empty array
            if (!$cartItems) {
                $cartItems = collect([]);
            }
            
            // Get admin name from settings or use default
            $adminName = 'The Bookshop Team';
            
            Mail::to($order->email)->send(new \App\Mail\OrderConfirmation($order, $cartItems, $order->total_amount));
            
            Log::info('Order confirmation email sent', ['order_id' => $order->id]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);
        }
    }

    /**
     * Get banks list for bank transfer
     */
    public function getBanks()
    {
        $result = $this->paystack->getBanks();
        
        if ($result['status']) {
            return response()->json([
                'success' => true,
                'banks' => $result['data']
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch banks'
        ]);
    }
}
