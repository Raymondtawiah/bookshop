<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PdfWatermarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected $pdfWatermarkService;

    public function __construct(PdfWatermarkService $pdfWatermarkService)
    {
        $this->pdfWatermarkService = $pdfWatermarkService;
    }

    /**
     * Process checkout with customer name for personalization.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
        ]);

        $cartItems = \App\Models\Cart::where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product_price * $item->quantity;
        });

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'total_amount' => $total,
            'status' => 'confirmed',
        ]);

        // Check if any cart item has a PDF template and generate personalized PDF
        foreach ($cartItems as $item) {
            if ($item->book && $item->book->pdf_file) {
                // Generate personalized PDF
                $fileName = $this->pdfWatermarkService->generateFileName(
                    $order->id,
                    $request->customer_name
                );

                $pdfPath = $this->pdfWatermarkService->addWatermark(
                    $item->book->pdf_file,
                    $request->customer_name,
                    $fileName
                );

                // Update order with personalized PDF path (use the first one found)
                if (!$order->personalized_pdf_path) {
                    $order->update(['personalized_pdf_path' => $pdfPath]);
                }
                break; // Only generate PDF for one book
            }
        }

        // Clear cart
        $cartItems->each->delete();

        return view('cart.checkout', compact('order', 'total'));
    }

    /**
     * Download personalized PDF.
     */
    public function downloadPdf(Order $order)
    {
        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        if (!$order->personalized_pdf_path) {
            abort(404, 'Personalized PDF not found');
        }

        $filePath = storage_path('app/public/' . $order->personalized_pdf_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath);
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
