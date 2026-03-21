<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Book;
use App\Services\OrderPdfService;
use App\Services\PassageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected OrderPdfService $orderPdfService;
    protected PassageService $passageService;

    /**
     * Constructor - inject OrderPdfService (Dependency Injection)
     * 
     * This follows the Dependency Inversion Principle (DIP) -
     * The controller depends on abstractions (services), not concrete implementations.
     */
    public function __construct(OrderPdfService $orderPdfService, PassageService $passageService)
    {
        $this->orderPdfService = $orderPdfService;
        $this->passageService = $passageService;
    }

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
        
        // Get all books with PDFs for selection
        $books = Book::whereNotNull('book_pdf')
            ->where('book_pdf', '!=', '')
            ->orderBy('title')
            ->get();
        
        // Get all passages for selection
        $passages = $this->passageService->getAllPassages();
        $passageNames = $this->passageService->getPassageNames();
        
        return view('admin.orders.show', compact('order', 'orderItems', 'books', 'passages', 'passageNames'));
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
     * Generate and send PDF to customer using selected book
     * 
     * This follows the Single Responsibility Principle (SRP) -
     * This method handles the specific use case of generating PDF from book and sending.
     */
    public function generateAndSendPdf(Request $request, $id)
    {
        $request->validate([
            'book_id' => 'required|integer|exists:books,id'
        ]);

        $order = Order::with('user')->findOrFail($id);
        $bookId = $request->book_id;

        // Use the OrderPdfService to generate and send PDF
        $result = $this->orderPdfService->generateAndSendPdf($order, $bookId);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }
    }

    /**
     * Generate and send PDF from text content
     * 
     * This allows admin to paste text or select passage and generate a PDF
     * with the customer's name at the bottom of each page.
     */
    public function generateFromText(Request $request, $id)
    {
        // Validate either passage or content is provided
        $request->validate([
            'passage' => 'nullable|string',
            'content' => 'nullable|string|min:1',
            'title' => 'nullable|string|max:255'
        ]);

        $order = Order::with('user')->findOrFail($id);
        
        // Check if passage is selected
        $content = '';
        $title = $request->title ?? 'Document';
        
        // First check if a passage is selected
        if (!empty($request->passage)) {
            // Directly read the passage file
            $passagePath = resource_path('passages/' . $request->passage . '.txt');
            if (file_exists($passagePath)) {
                $content = file_get_contents($passagePath);
                // Use passage name as title if no custom title provided
                if (empty($request->title)) {
                    $title = str_replace(['_', '-'], ' ', ucwords($request->passage, ' _-'));
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'Selected passage not found.');
            }
        } elseif (!empty($request->content)) {
            // Use pasted content
            $content = $request->content;
        } else {
            return redirect()->back()->withInput()->with('error', 'Please select a passage or paste some content for the PDF.');
        }

        try {
            // Use the OrderPdfService to generate PDF from text and send
            $result = $this->orderPdfService->generateFromTextAndSend($order, $content, $title);

            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->withInput()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
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
        
        // Get full path from storage
        $fullPath = Storage::disk('public')->path($pdfPath);
        
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
                        ->subject('Your Visa Resource Order #' . ($order->order_number ?? $order->id))
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
