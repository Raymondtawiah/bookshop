<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

/**
 * Order PDF Service
 *
 * This follows the Facade Pattern and Dependency Inversion Principle (DIP) -
 * Provides a simple interface to complex subsystems (PDF generation + Email).
 *
 * This also follows the Single Responsibility Principle (SRP) -
 * This class is only responsible for orchestrating the PDF generation and email flow.
 */
class OrderPdfService
{
    protected PdfGeneratorService $pdfGenerator;

    protected CustomerEmailService $emailService;

    /**
     * Constructor - inject dependencies (Dependency Injection)
     */
    public function __construct(
        PdfGeneratorService $pdfGenerator,
        CustomerEmailService $emailService
    ) {
        $this->pdfGenerator = $pdfGenerator;
        $this->emailService = $emailService;
    }

    /**
     * Generate PDF with customer name and send to customer
     *
     * This is the main method that orchestrates the entire flow:
     * 1. Get the book
     * 2. Generate PDF with customer name
     * 3. Send email with PDF attachment
     * 4. Update order status
     *
     * @return array ['success' => bool, 'message' => string, 'pdf_path' => string|null]
     */
    public function generateAndSendPdf(Order $order, int $bookId): array
    {
        try {
            // 1. Get the book
            $book = Book::findOrFail($bookId);

            // 2. Check if book has source PDF
            if (! $this->pdfGenerator->hasSourcePdf($book)) {
                return [
                    'success' => false,
                    'message' => "Book '{$book->title}' does not have a PDF file attached.",
                    'pdf_path' => null,
                ];
            }

            // 3. Generate PDF with customer name at bottom
            Log::info("Generating PDF for order #{$order->id}, book: {$book->title}");
            $pdfPath = $this->pdfGenerator->generateWithWatermark($book, $order);

            // 4. Send email with PDF
            $subject = "Your {$book->title} - Order #".($order->order_number ?? $order->id);
            $emailSent = $this->emailService->sendEmailWithAttachment(
                $order,
                $pdfPath,
                $subject,
                'emails.order-confirmation'
            );

            if (! $emailSent) {
                return [
                    'success' => false,
                    'message' => 'PDF generated but failed to send email.',
                    'pdf_path' => $pdfPath,
                ];
            }

            // 5. Update order status
            $order->update([
                'status' => 'confirmed',
                'pdf_sent' => true,
                'pdf_sent_at' => now(),
            ]);

            Log::info("PDF sent successfully to {$order->email} for order #{$order->id}");

            return [
                'success' => true,
                'message' => 'PDF generated and sent to customer successfully!',
                'pdf_path' => $pdfPath,
            ];

        } catch (ModelNotFoundException $e) {
            Log::error("Book not found: {$bookId}");

            return [
                'success' => false,
                'message' => 'Selected book not found.',
                'pdf_path' => null,
            ];
        } catch (\Exception $e) {
            Log::error('PDF generation and send failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to generate and send PDF: '.$e->getMessage(),
                'pdf_path' => null,
            ];
        }
    }

    /**
     * Generate PDF only (without sending)
     */
    public function generatePdfOnly(Order $order, int $bookId): array
    {
        try {
            $book = Book::findOrFail($bookId);

            if (! $this->pdfGenerator->hasSourcePdf($book)) {
                return [
                    'success' => false,
                    'message' => "Book '{$book->title}' does not have a PDF file attached.",
                    'pdf_path' => null,
                ];
            }

            $pdfPath = $this->pdfGenerator->generateWithWatermark($book, $order);

            return [
                'success' => true,
                'message' => 'PDF generated successfully!',
                'pdf_path' => $pdfPath,
            ];

        } catch (\Exception $e) {
            Log::error('PDF generation failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to generate PDF: '.$e->getMessage(),
                'pdf_path' => null,
            ];
        }
    }

    /**
     * Send existing PDF to customer
     */
    public function sendExistingPdf(Order $order, string $pdfPath, ?string $subject = null): array
    {
        try {
            $subject = $subject ?? 'Your Order #'.($order->order_number ?? $order->id);

            $emailSent = $this->emailService->sendEmailWithAttachment(
                $order,
                $pdfPath,
                $subject
            );

            if (! $emailSent) {
                return [
                    'success' => false,
                    'message' => 'Failed to send email.',
                    'pdf_path' => $pdfPath,
                ];
            }

            // Update order status
            $order->update([
                'status' => 'confirmed',
                'pdf_sent' => true,
                'pdf_sent_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'PDF sent to customer successfully!',
                'pdf_path' => $pdfPath,
            ];

        } catch (\Exception $e) {
            Log::error('PDF send failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to send PDF: '.$e->getMessage(),
                'pdf_path' => $pdfPath,
            ];
        }
    }

    /**
     * Generate PDF from text content and send to customer
     *
     * This allows admin to paste text/passages and generate a PDF
     * with the customer's name at the bottom of each page.
     *
     * @param  string  $content  The text content to convert to PDF
     * @param  string  $title  Optional title for the PDF
     */
    public function generateFromTextAndSend(Order $order, string $content, string $title = 'Document'): array
    {
        try {
            if (empty(trim($content))) {
                return [
                    'success' => false,
                    'message' => 'Please enter some content for the PDF.',
                    'pdf_path' => null,
                ];
            }

            Log::info("Generating PDF from text for order #{$order->id}");

            // Generate PDF from text with customer name at bottom
            $pdfPath = $this->pdfGenerator->generateFromText($content, $order, $title);

            // Send email with PDF
            $subject = "{$title} - Order #".($order->order_number ?? $order->id);
            $emailSent = $this->emailService->sendEmailWithAttachment(
                $order,
                $pdfPath,
                $subject,
                'emails.order-confirmation'
            );

            if (! $emailSent) {
                return [
                    'success' => false,
                    'message' => 'PDF generated but failed to send email.',
                    'pdf_path' => $pdfPath,
                ];
            }

            // Update order status
            $order->update([
                'status' => 'confirmed',
                'pdf_sent' => true,
                'pdf_sent_at' => now(),
            ]);

            Log::info("PDF from text sent successfully to {$order->email} for order #{$order->id}");

            return [
                'success' => true,
                'message' => 'PDF generated and sent to customer successfully!',
                'pdf_path' => $pdfPath,
            ];

        } catch (\Exception $e) {
            Log::error('PDF generation from text and send failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to generate and send PDF: '.$e->getMessage(),
                'pdf_path' => null,
            ];
        }
    }
}
