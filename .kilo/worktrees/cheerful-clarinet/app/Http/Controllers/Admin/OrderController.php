<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendPdfEmailJob;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderPdfService;
use App\Services\PassageService;
use App\Services\PdfGeneratorService;
use App\Services\WordToPdfService;
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

        $orderItems = $order->order_items;

        return view('admin.orders.show', compact('order', 'orderItems'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
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
            'book_id' => 'required|integer|exists:books,id',
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
            'title' => 'nullable|string|max:255',
        ]);

        $order = Order::with('user')->findOrFail($id);

        // Get customer name for footer
        $customerName = $order->customer_name ?? 'Customer';
        $validDate = date('Y-m-d');

        // Check if passage is selected
        $content = '';
        $title = $request->title ?? 'Document';

        // First check if a passage is selected
        if (! empty($request->passage)) {
            // Use PassageService to get passage content
            $passageService = app(PassageService::class);
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
        } elseif (! empty($request->content)) {
            // Use pasted content
            $content = $request->content;
        } else {
            return redirect()->back()->withInput()->with('error', 'Please select a passage or paste some content for the PDF.');
        }

        // Generate PDF with styled content and customer name footer
        try {
            $pdf = new TCPDF;
            $pdf->SetFont('helvetica', '', 12);

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set margins and auto page break
            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 30);

            $pdf->AddPage();

            // Add title with styling
            $pdf->SetFont('helvetica', 'B', 20);
            $pdf->Cell(0, 10, $title, 0, true, 'C');
            $pdf->Ln(10);

            // Parse and add styled content
            $this->addStyledContent($pdf, $content);

            // Add customer name footer
            $this->addCustomerFooter($pdf, $customerName, $validDate);

            // Save PDF
            $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $customerName);
            $filename = $title.'_'.$sanitizedName.'_'.time().'.pdf';
            $storagePath = public_path('storage/books/generated');
            if (! is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            $pdfPath = $storagePath.'/'.$filename;
            $pdf->Output($pdfPath, 'F');

            // Send email with PDF
            try {
                $cartItems = Cart::where('user_id', $order->user_id)->get();
                \Mail::send('emails.order-confirmation',
                    ['order' => $order, 'user' => $order->user, 'cartItems' => $cartItems, 'adminName' => 'Admin'],
                    function ($message) use ($order, $pdfPath, $filename, $title) {
                        $message->to($order->email, $order->customer_name)
                            ->subject($title.' - Order #'.($order->order_number ?? $order->id))
                            ->attach($pdfPath, ['as' => $filename, 'mime' => 'application/pdf']);
                    });
            } catch (\Exception $e) {
                \Log::error('Email error: '.$e->getMessage());
            }

            return redirect()->back()->with('success', 'PDF generated and sent successfully!');

        } catch (\Exception $e) {
            \Log::error('PDF error: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error: '.$e->getMessage());
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
     * Add styled content with proper formatting to PDF
     * Handles headers, lists, checkboxes, horizontal rules
     */
    protected function addStyledContent(TCPDF $pdf, string $content): void
    {
        $lines = explode('\n', $content);
        $inList = false;

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // Skip empty lines but maintain spacing
            if (empty($trimmedLine)) {
                $pdf->Ln(4);
                $inList = false;

                continue;
            }

            // Main header (# Title)
            if (preg_match('/^#\s+(.*)$/', $trimmedLine, $matches)) {
                $pdf->Ln(8);
                $pdf->SetFont('helvetica', 'B', 22);
                $pdf->SetTextColor(31, 41, 59); // dark gray
                $pdf->Cell(0, 12, $matches[1], 0, true, 'C');
                $pdf->Ln(4);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                $inList = false;

                continue;
            }

            // Section header (## Section)
            if (preg_match('/^##\s+(.*)$/', $trimmedLine, $matches)) {
                $pdf->Ln(10);
                $pdf->SetFont('helvetica', 'B', 16);
                $pdf->SetTextColor(31, 41, 59);
                $pdf->Cell(0, 10, $matches[1], 0, true, 'L');
                $pdf->SetDrawColor(99, 102, 241); // indigo
                $pdf->SetLineWidth(0.5);
                $pdf->Line($pdf->GetX(), $pdf->GetY() + 2, $pdf->GetX() + 180, $pdf->GetY() + 2);
                $pdf->Ln(4);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                $inList = false;

                continue;
            }

            // Subsection (### Subsection)
            if (preg_match('/^###\s+(.*)$/', $trimmedLine, $matches)) {
                $pdf->Ln(6);
                $pdf->SetFont('helvetica', 'B', 14);
                $pdf->SetTextColor(55, 65, 81);
                $pdf->Cell(0, 8, $matches[1], 0, true, 'L');
                $pdf->Ln(2);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                $inList = false;

                continue;
            }

            // Horizontal rule (--- or ***)
            if (preg_match('/^(-{3,}|\*{3,})$/', $trimmedLine)) {
                $pdf->Ln(8);
                $pdf->SetDrawColor(156, 163, 175); // gray
                $pdf->SetLineWidth(0.3);
                $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 170, $pdf->GetY());
                $pdf->Ln(8);
                $inList = false;

                continue;
            }

            // Checkbox unchecked (- [ ] item)
            if (preg_match('/^-\s+\[\s*\]\s+(.*)$/', $trimmedLine, $matches)) {
                if (! $inList) {
                    $pdf->Ln(4);
                    $inList = true;
                }
                // Draw checkbox
                $pdf->Rect($pdf->GetX() + 2, $pdf->GetY() + 2, 4, 4);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(75, 85, 99);
                $pdf->Cell(10, 10, '', 0, 0);
                $pdf->Cell(0, 10, $matches[1], 0, true);
                $pdf->SetTextColor(0, 0, 0);

                continue;
            }

            // Checkbox checked (- [x] item)
            if (preg_match('/^-\s+\[x\]\s+(.*)$/', $trimmedLine, $matches)) {
                if (! $inList) {
                    $pdf->Ln(4);
                    $inList = true;
                }
                // Draw checked checkbox
                $pdf->Rect($pdf->GetX() + 2, $pdf->GetY() + 2, 4, 4);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(22, 163, 74); // green
                $pdf->Cell(10, 10, '', 0, 0);
                $pdf->Cell(0, 10, $matches[1], 0, true);
                $pdf->SetTextColor(0, 0, 0);

                continue;
            }

            // Bullet list item (- item)
            if (preg_match('/^-\s+(.*)$/', $trimmedLine, $matches)) {
                if (! $inList) {
                    $pdf->Ln(4);
                    $inList = true;
                }
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(55, 65, 81);
                $pdf->Cell(8, 10, '•', 0, 0);
                $pdf->Cell(0, 10, $matches[1], 0, true);
                $pdf->SetTextColor(0, 0, 0);

                continue;
            }

            // Numbered list item (1. item)
            if (preg_match('/^(\d+)\.\s+(.*)$/', $trimmedLine, $matches)) {
                if (! $inList) {
                    $pdf->Ln(4);
                    $inList = true;
                }
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(55, 65, 81);
                $pdf->Cell(8, 10, $matches[1].'.', 0, 0);
                $pdf->Cell(0, 10, $matches[2], 0, true);
                $pdf->SetTextColor(0, 0, 0);

                continue;
            }

            // Bold text (**text**)
            if (preg_match('/^\*\*(.*)\*\*$/', $trimmedLine, $matches)) {
                $pdf->Ln(4);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->SetTextColor(31, 41, 59);
                $pdf->MultiCell(0, 10, $matches[1]);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                $inList = false;

                continue;
            }

            // Italic text (*text*)
            if (preg_match('/^\*([^\*]+)\*$/', $trimmedLine, $matches)) {
                $pdf->Ln(4);
                $pdf->SetFont('helvetica', 'I', 12);
                $pdf->SetTextColor(75, 85, 99);
                $pdf->MultiCell(0, 10, $matches[1]);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                $inList = false;

                continue;
            }

            // Regular paragraph
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 10, $line);
            $inList = false;
        }
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
                'message' => 'No passage selected',
            ]);
        }

        $passageService = app(PassageService::class);
        $content = $passageService->getPassage($passage);

        if ($content === null) {
            return response()->json([
                'success' => false,
                'message' => 'Passage not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'content' => $content,
            'name' => $passageService->getPassageName($passage),
        ]);
    }

    /**
     * Send PDF to customer
     */
    public function sendPdf(Request $request, $id)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:51200',
        ]);

        $order = Order::with('user')->findOrFail($id);

        // Store the uploaded PDF
        $pdfFile = $request->file('pdf_file');
        $filename = 'order-'.($order->order_number ?? $order->id).'.pdf';
        $pdfPath = $pdfFile->storeAs('books/pdfs', $filename, 'public');

        // Get full path from storage
        $fullPath = Storage::disk('public')->path($pdfPath);

        Log::info('PDF stored at: '.$fullPath);

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
                    'adminName' => $adminName,
                ],
                function ($message) use ($order, $fullPath, $filename) {
                    $message->to($order->email, $order->customer_name)
                        ->subject('Your Visa Resource Order #'.($order->order_number ?? $order->id))
                        ->attach($fullPath, [
                            'as' => $filename,
                            'mime' => 'application/pdf',
                        ]);
                }
            );

            Log::info('Email sent to: '.$order->email);

            // Update order status to confirmed and mark PDF as sent
            $order->update([
                'status' => 'confirmed',
                'pdf_sent' => true,
                'pdf_sent_at' => now(),
            ]);

            return redirect()->back()->with('success', 'PDF sent to customer successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to send PDF: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Failed to send PDF: '.$e->getMessage());
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

    /**
     * Upload Word file, convert to PDF with customer name, and send to customer
     */
    public function uploadWordPdf(Request $request, $id)
    {
        $request->validate([
            'word_file' => 'required|array|max:10240',
            'word_file.*' => 'file|mimes:doc,docx|max:10240',
        ]);

        $order = Order::with('user')->findOrFail($id);

        $wordFiles = $request->file('word_file');

        if (empty($wordFiles)) {
            return redirect()->back()->withInput()->with('error', 'Please select at least one Word document.');
        }

        $pdfPaths = [];
        $titles = [];

        try {
            $wordService = app(WordToPdfService::class);
            $tempDir = sys_get_temp_dir();
            $fullPath = public_path('storage/books/generated');

            if (! is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            foreach ($wordFiles as $wordFile) {
                $originalName = $wordFile->getClientOriginalName();
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $title = pathinfo($originalName, PATHINFO_FILENAME);
                $titles[] = $title;

                if (! in_array($extension, ['doc', 'docx'])) {
                    continue;
                }

                $tempDoc = $tempDir.'/'.uniqid().'.'.$extension;
                copy($wordFile->getPathname(), $tempDoc);

                $filename = time().'_'.uniqid().'_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $title).'.pdf';
                $outputPath = $fullPath.'/'.$filename;

                $wordService->convertToPdfUsingPhpWord($tempDoc, $outputPath, $title, null, false);

                if (file_exists($tempDoc)) {
                    unlink($tempDoc);
                }

                if (file_exists($outputPath)) {
                    $pdfPaths[] = [
                        'path' => $outputPath,
                        'filename' => $filename,
                        'title' => $title,
                    ];
                }
            }

            if (empty($pdfPaths)) {
                return redirect()->back()->withInput()->with('error', 'Failed to convert Word files to PDF. Please try again.');
            }

            // Send email with PDF attachments using queue for faster delivery
            try {
                $user = $order->user;
                
                // Dispatch to queue for faster delivery
                SendPdfEmailJob::dispatch($user, $pdfPaths, $order->id);

                $order->update([
                    'status' => 'confirmed',
                    'pdf_sent' => true,
                    'pdf_sent_at' => now(),
                ]);

                return redirect()->back()->with('success', count($pdfPaths).' file(s) converted and queued to send to customer!');

            } catch (\Exception $e) {
                Log::error('Failed to queue PDF email: '.$e->getMessage());
                
                // Still update order even if email fails
                $order->update([
                    'status' => 'confirmed',
                    'pdf_sent' => true,
                    'pdf_sent_at' => now(),
                ]);

                return redirect()->back()->with('success', count($pdfPaths).' file(s) converted! (Email may be delayed)');
            }

        } catch (\Exception $e) {
            Log::error('Word to PDF conversion failed: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Failed to convert Word to PDF: '.$e->getMessage());
        }
    }

    /**
     * Upload PDF files and send to customer
     */
    public function uploadPdf(Request $request, $id)
    {
        $request->validate([
            'pdf_file' => 'required|array|max:10240',
            'pdf_file.*' => 'file|mimes:pdf|max:10240',
        ]);

        $order = Order::with('user')->findOrFail($id);

        $pdfFiles = $request->file('pdf_file');

        if (empty($pdfFiles)) {
            return redirect()->back()->withInput()->with('error', 'Please select at least one PDF file.');
        }

        $pdfPaths = [];
        $titles = [];

        try {
            $fullPath = public_path('storage/books/generated');

            if (! is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            foreach ($pdfFiles as $pdfFile) {
                $originalName = $pdfFile->getClientOriginalName();
                $title = pathinfo($originalName, PATHINFO_FILENAME);
                $titles[] = $title;

                $filename = time().'_'.uniqid().'_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $title).'.pdf';
                $destinationPath = $fullPath.'/'.$filename;

                $pdfFile->move($fullPath, $filename);

                if (file_exists($destinationPath)) {
                    $pdfPaths[] = [
                        'path' => $destinationPath,
                        'filename' => $filename,
                        'title' => $title,
                    ];
                }
            }

            if (empty($pdfPaths)) {
                return redirect()->back()->withInput()->with('error', 'Failed to upload PDF files. Please try again.');
            }

            try {
                $user = $order->user;
                
                SendPdfEmailJob::dispatch($user, $pdfPaths, $order->id);

                $order->update([
                    'status' => 'confirmed',
                    'pdf_sent' => true,
                    'pdf_sent_at' => now(),
                ]);

                return redirect()->back()->with('success', count($pdfPaths).' file(s) uploaded and queued to send to customer!');

            } catch (\Exception $e) {
                Log::error('Failed to queue PDF email: '.$e->getMessage());
                
                $order->update([
                    'status' => 'confirmed',
                    'pdf_sent' => true,
                    'pdf_sent_at' => now(),
                ]);

                return redirect()->back()->with('success', count($pdfPaths).' file(s) uploaded! (Email may be delayed)');
            }

        } catch (\Exception $e) {
            Log::error('PDF upload failed: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Failed to upload PDF: '.$e->getMessage());
        }
    }
}
