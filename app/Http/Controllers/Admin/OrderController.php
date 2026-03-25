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
use TCPDF;

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
        
        // Get order items from the order - now using the accessor which properly handles the JSON
        $orderItems = $order->order_items;
        
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
        
        // Get customer name for footer
        $customerName = $order->customer_name ?? 'Customer';
        $validDate = date('Y-m-d');
        
        // Check if passage is selected
        $content = '';
        $title = $request->title ?? 'Document';
        
        // First check if a passage is selected
        if (!empty($request->passage)) {
            // Use PassageService to get passage content
            $passageService = app(\App\Services\PassageService::class);
            $passageContent = $passageService->getPassage($request->passage);
            
            if ($passageContent !== null) {
                $content = $passageContent;
                // Use passage name as title if no custom title provided
                if (empty($request->title)) {
                    $title = $passageService->getPassageName($request->passage, $title);
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

        // Generate PDF with styled content and customer name footer
        try {
            $pdf = new \TCPDF();
            $pdf->SetFont('helvetica', '', 12);
            
            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // Set margins and auto page break
            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(TRUE, 30);
            
            $pdf->AddPage();
            
            // Add title with styling
            $pdf->SetFont('helvetica', 'B', 20);
            $pdf->Cell(0, 10, $title, 0, true, 'C');
            $pdf->Ln(10);
            
            // Add styled content
            $pdf->SetFont('helvetica', '', 12);
            $pdf->MultiCell(0, 10, $content);
            
            // Add customer name footer
            $this->addCustomerFooter($pdf, $customerName, $validDate);
            
            // Save PDF
            $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $customerName);
            $filename = $title . '_' . $sanitizedName . '_' . time() . '.pdf';
            $storagePath = public_path('storage/books/generated');
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            $pdfPath = $storagePath . '/' . $filename;
            $pdf->Output($pdfPath, 'F');
            
            // Send email with PDF
            try {
                $cartItems = \App\Models\Cart::where('user_id', $order->user_id)->get();
                \Mail::send('emails.order-confirmation', 
                    ['order' => $order, 'user' => $order->user, 'cartItems' => $cartItems, 'adminName' => 'Admin'], 
                    function ($message) use ($order, $pdfPath, $filename, $title) {
                        $message->to($order->email, $order->customer_name)
                            ->subject($title . ' - Order #' . ($order->order_number ?? $order->id))
                            ->attach($pdfPath, ['as' => $filename, 'mime' => 'application/pdf']);
                });
            } catch (\Exception $e) {
                \Log::error('Email error: ' . $e->getMessage());
            }
            
            return redirect()->back()->with('success', 'PDF generated and sent successfully!');
            
        } catch (\Exception $e) {
            \Log::error('PDF error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Add customer name footer to PDF
     */
    protected function addCustomerFooter(TCPDF $pdf, string $customerName, string $validDate): void
    {
        $pageWidth = 210; // A4 width in mm
        $pageHeight = 297; // A4 height in mm
        
        // Get actual page dimensions if available
        try {
            if (method_exists($pdf, 'getPageWidth') && $pdf->getPageWidth()) {
                $pageWidth = $pdf->getPageWidth();
            }
            if (method_exists($pdf, 'getPageHeight') && $pdf->getPageHeight()) {
                $pageHeight = $pdf->getPageHeight();
            }
        } catch (\Exception $e) {
            // Use defaults
        }
        
        $pdf->SetFont('helvetica', 'I', 9);
        $pdf->SetTextColor(128, 128, 128);
        
        // Position at bottom of page
        $pdf->SetXY(0, $pageHeight - 20);
        $footerText = "Licensed to: {$customerName} | Valid Date: {$validDate}";
        $pdf->Cell($pageWidth, 8, $footerText, 0, 1, 'C', false);
        
        // Reset text color
        $pdf->SetTextColor(0, 0, 0);
    }

    /**
     * Preview passage content (API endpoint)
     */
    public function previewPassage(Request $request)
    {
        $passage = $request->query('passage');
        
        if (empty($passage)) {
            return response()->json([
                'success' => false,
                'message' => 'No passage selected'
            ]);
        }
        
        $passageService = app(\App\Services\PassageService::class);
        $content = $passageService->getPassage($passage);
        
        if ($content === null) {
            return response()->json([
                'success' => false,
                'message' => 'Passage not found'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'content' => $content,
            'name' => $passageService->getPassageName($passage)
        ]);
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
