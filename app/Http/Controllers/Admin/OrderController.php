<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        
        // Get order items from cart (since we store them there temporarily)
        $orderItems = Cart::where('user_id', $order->user_id)->get();
        
        return view('admin.orders.show', compact('order', 'orderItems'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Send PDF to customer
     */
    public function sendPdf(Request $request, $id)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:51200'
        ]);

        $order = Order::with('user')->findOrFail($id);
        
        // Store the uploaded PDF
        $pdfFile = $request->file('pdf_file');
        $filename = 'order-' . ($order->order_number ?? $order->id) . '.pdf';
        $pdfPath = $pdfFile->storeAs('books/pdfs', $filename, 'public');
        
        // Get full path
        $fullPath = storage_path('app/public/' . $pdfPath);
        
        Log::info('PDF stored at: ' . $fullPath);
        
        // Send email with PDF attachment
        try {
            // Get cart items for this order
            $cartItems = Cart::where('user_id', $order->user_id)->get();
            $adminName = auth()->user()->name ?? 'Admin';
            
            Mail::send(
                'emails.order-confirmation',
                [
                    'order' => $order, 
                    'user' => $order->user,
                    'cartItems' => $cartItems,
                    'adminName' => $adminName
                ],
                function ($message) use ($order, $fullPath, $filename) {
                    $message->to($order->email, $order->customer_name)
                        ->subject('Your Bookshop Order #' . ($order->order_number ?? $order->id))
                        ->attach($fullPath, [
                            'as' => $filename,
                            'mime' => 'application/pdf',
                        ]);
                }
            );
            
            Log::info('Email sent to: ' . $order->email);
            
            // Update order status to confirmed and mark PDF as sent
            $order->update([
                'status' => 'confirmed',
                'pdf_sent' => true,
                'pdf_sent_at' => now()
            ]);

            return redirect()->back()->with('success', 'PDF sent to customer successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to send PDF: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to send PDF: ' . $e->getMessage());
        }
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully!');
    }
}
