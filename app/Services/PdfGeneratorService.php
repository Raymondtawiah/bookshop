<?php

namespace App\Services;

use App\Contracts\PdfGeneratorInterface;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF;

/**
 * PDF Generator Service
 * 
 * This follows the Single Responsibility Principle (SRP) -
 * This class is only responsible for PDF generation with watermarks.
 * 
 * It also follows the Open/Closed Principle (OCP) -
 * New watermark styles can be added without modifying existing code.
 */
class PdfGeneratorService implements PdfGeneratorInterface
{
    /**
     * Storage path for generated PDFs
     */
    protected string $storagePath = 'books/generated';

    /**
     * Generate a PDF with customer name watermark
     * 
     * @param Book $book The book to generate PDF from
     * @param Order $order The order containing customer details
     * @return string Path to the generated PDF file
     * @throws \Exception If PDF generation fails
     */
    public function generateWithWatermark(Book $book, Order $order): string
    {
        $sourcePdf = $this->getSourcePdfPath($book);
        
        if (!$sourcePdf || !file_exists($sourcePdf)) {
            throw new \Exception("Source PDF not found for book: {$book->title}");
        }

        try {
            // Initialize FPDI
            $pdf = new Fpdi();
            
            // Get the number of pages
            $pageCount = $pdf->setSourceFile($sourcePdf);
            
            // Add watermark to each page
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                
                // Get the page size
                $size = $pdf->getTemplateSize($templateId);
                
                // Add a new page with the same size
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                
                // Use the template
                $pdf->useTemplate($templateId);
                
                // Add watermark - customer name
                $this->addWatermark($pdf, $order->customer_name, $size['width'], $size['height']);
            }
            
            // Generate unique filename
            $filename = $this->generateFilename($book, $order);
            
            // Save to public/storage/books/generated/ directory
            $fullPath = public_path("storage/{$this->storagePath}");
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Save the PDF
            $outputPath = "{$fullPath}/{$filename}";
            $pdf->Output($outputPath, 'F');
            
            Log::info("PDF generated successfully: {$outputPath}");
            
            // Return path relative to public/storage
            return "{$this->storagePath}/{$filename}";
            
        } catch (\Exception $e) {
            Log::error("PDF generation failed: " . $e->getMessage());
            throw new \Exception("Failed to generate PDF: " . $e->getMessage());
        }
    }

    /**
     * Generate PDF from text content with customer name
     * 
     * @param string $content The text content to convert to PDF
     * @param Order $order The order containing customer details
     * @param string $title Optional title for the PDF
     * @return string Path to the generated PDF file
     * @throws \Exception If PDF generation fails
     */
    public function generateFromText(string $content, Order $order, string $title = 'Document'): string
    {
        try {
            // Initialize TCPDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // Set document information
            $pdf->SetCreator('Bookshop');
            $pdf->SetAuthor('Bookshop Admin');
            $pdf->SetTitle($title);
            $pdf->SetSubject('Customer Document');
            
            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // Set margins
            $pdf->SetMargins(20, 20, 20);
            
            // Set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, 25);
            
            // Add a page
            $pdf->AddPage();
            
            // Set font
            $pdf->SetFont('helvetica', '', 12);
            
            // Add title if provided
            if ($title) {
                $pdf->SetFont('helvetica', 'B', 18);
                $pdf->Cell(0, 10, $title, 0, true, 'C');
                $pdf->Ln(10);
                $pdf->SetFont('helvetica', '', 12);
            }
            
            // Add the content
            $pdf->MultiCell(0, 10, $content);
            
            // Add customer name at the bottom of the last page
            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->SetTextColor(128, 128, 128);
            $pdf->SetY(-20);
            $pdf->Cell(0, 10, "Licensed to: {$order->customer_name} | Valid: " . date('Y-m-d'), 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
            
            // Generate unique filename
            $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $order->customer_name);
            $filename = sprintf('%s_%s_%d.pdf', $title, $sanitizedName, time());
            
            // Save to public/storage/books/generated/ directory
            $fullPath = public_path("storage/{$this->storagePath}");
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Save the PDF
            $outputPath = "{$fullPath}/{$filename}";
            $pdf->Output($outputPath, 'F');
            
            Log::info("PDF generated from text successfully: {$outputPath}");
            
            // Return path relative to public/storage
            return "{$this->storagePath}/{$filename}";
            
        } catch (\Exception $e) {
            Log::error("PDF generation from text failed: " . $e->getMessage());
            throw new \Exception("Failed to generate PDF from text: " . $e->getMessage());
        }
    }

    /**
     * Generate a simple PDF without watermark
     * 
     * @param Book $book The book to generate PDF from
     * @return string Path to the generated PDF file
     */
    public function generateSimple(Book $book): string
    {
        $sourcePdf = $this->getSourcePdfPath($book);
        
        if (!$sourcePdf || !file_exists($sourcePdf)) {
            throw new \Exception("Source PDF not found for book: {$book->title}");
        }

        // For simple generation, just copy the file with a new name
        $filename = time() . '_' . slug($book->title) . '.pdf';
        $fullPath = public_path("storage/{$this->storagePath}");
        
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        copy($sourcePdf, "{$fullPath}/{$filename}");
        
        return "{$this->storagePath}/{$filename}";
    }

    /**
     * Check if the book has a source PDF file
     * 
     * @param Book $book
     * @return bool
     */
    public function hasSourcePdf(Book $book): bool
    {
        if (!$book->book_pdf) {
            return false;
        }
        
        $sourcePath = $this->getSourcePdfPath($book);
        return $sourcePath && file_exists($sourcePath);
    }

    /**
     * Get the full path to the source PDF
     * 
     * @param Book $book
     * @return string|null
     */
    protected function getSourcePdfPath(Book $book): ?string
    {
        if (!$book->book_pdf) {
            return null;
        }
        
        // Check if it's a full path
        if (file_exists($book->book_pdf)) {
            return $book->book_pdf;
        }
        
        // Check in storage
        $storagePath = storage_path("app/public/books/{$book->book_pdf}");
        if (file_exists($storagePath)) {
            return $storagePath;
        }
        
        // Try without the books prefix
        $altPath = storage_path("app/public/{$book->book_pdf}");
        if (file_exists($altPath)) {
            return $altPath;
        }
        
        return null;
    }

    /**
     * Add customer name at the bottom of each page
     * 
     * This method follows the Open/Closed Principle -
     * Can be extended to add different footer styles without modifying existing code.
     * 
     * @param Fpdi $pdf
     * @param string $customerName
     * @param float $pageWidth
     * @param float $pageHeight
     */
    protected function addWatermark(Fpdi $pdf, string $customerName, float $pageWidth, float $pageHeight): void
    {
        // Add customer name at the bottom of the page
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->SetTextColor(128, 128, 128);
        
        // Position at bottom center
        $pdf->SetXY(0, $pageHeight - 20);
        
        // Add licensed to text
        $footerText = "Licensed to: {$customerName} | Valid: " . date('Y-m-d');
        $pdf->Cell($pageWidth, 10, $footerText, 0, 1, 'C', false);
        
        // Reset text color
        $pdf->SetTextColor(0, 0, 0);
    }

    /**
     * Generate a unique filename for the PDF
     * 
     * @param Book $book
     * @param Order $order
     * @return string
     */
    protected function generateFilename(Book $book, Order $order): string
    {
        $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $order->customer_name);
        $sanitizedTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $book->title);
        
        return sprintf(
            '%s_%s_%s_%d.pdf',
            $sanitizedTitle,
            $sanitizedName,
            date('YmdHis'),
            $order->id
        );
    }
}
