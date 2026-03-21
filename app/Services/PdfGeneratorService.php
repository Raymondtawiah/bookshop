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
                
                // Add watermark - customer name (first page gets special message)
                $this->addWatermark($pdf, $order->customer_name, $size['width'], $size['height'], $pageNo === 1);
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
            Log::info("PDF Gen: Starting - Order: {$order->id}, Title: {$title}");
            
            // Ensure content is a string
            if (!is_string($content)) {
                Log::error("PDF Gen: Content is not a string, type: " . gettype($content));
                throw new \Exception("Content must be a string, got: " . gettype($content));
            }
            
            // Store customer name for use in footer
            $customerName = $order->customer_name ?? 'Customer';
            Log::info("PDF Gen: Customer name: {$customerName}");
            $validDate = date('Y-m-d');
            
            // Initialize TCPDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // Set document information
            $pdf->SetCreator('Visa Resources');
            $pdf->SetAuthor('Visa Resources');
            $pdf->SetTitle($title);
            $pdf->SetSubject('Customer Document');
            
            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // Set margins (left, top, right) - leave space for footer
            $pdf->SetMargins(20, 20, 20);
            
            // Set bottom margin for footer space
            $pdf->SetAutoPageBreak(TRUE, 30);
            
            // Set page number callback
            $pdf->setPageMark();
            
            // Add first page
            $pdf->AddPage();
            
            // Set font for title
            $pdf->SetFont('helvetica', 'B', 18);
            
            // Add title if provided
            if ($title) {
                $pdf->Cell(0, 10, $title, 0, true, 'C');
                $pdf->Ln(5);
            }
            
            // Add the content
            $pdf->SetFont('helvetica', '', 12);
            $pdf->MultiCell(0, 10, $content);
            
            // Add footer on current (last) page
            $this->addTextFooter($pdf, $customerName, $validDate, true);
            
            // Generate unique filename
            $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $customerName);
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
     * Add footer text to each page of the PDF
     * 
     * @param TCPDF $pdf
     * @param string $customerName
     * @param string $validDate
     * @param bool $isFirstPage
     */
    protected function addTextFooter(TCPDF $pdf, string $customerName, string $validDate, bool $isFirstPage = false): void
    {
        // Use fixed A4 dimensions in mm as fallback
        $pageWidth = 210; // A4 width in mm
        $pageHeight = 297; // A4 height in mm
        
        // Try to get actual page dimensions
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
        
        // Define footer margins
        $footerBottom = 10; // 10mm from bottom
        $footerHeight = 18;
        
        // Move to footer position
        $y = $pageHeight - $footerBottom - $footerHeight;
        $pdf->SetY($y);
        
        // If first page, add "This book is personally customized for..."
        if ($isFirstPage) {
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(0, 0, 128); // Dark blue for first line
            $pdf->Cell(0, 8, "This book is personally customized for {$customerName}", 0, 1, 'C');
        }
        
        // Add "Licensed to: Customer Name | Valid: Date" on EVERY page
        $pdf->SetFont('helvetica', 'B', 10); // Bold font
        $pdf->SetTextColor(0, 0, 0); // Black color
        $pdf->Cell(0, 8, "Licensed to: {$customerName} | Valid: {$validDate}", 0, 1, 'C');
        
        // Reset text color
        $pdf->SetTextColor(0, 0, 0);
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
     * @param bool $isFirstPage
     */
    protected function addWatermark(Fpdi $pdf, string $customerName, float $pageWidth, float $pageHeight, bool $isFirstPage = false): void
    {
        $validDate = date('Y-m-d');
        
        // If first page, add "This book is personally customized for..."
        if ($isFirstPage) {
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(0, 0, 128); // Dark blue for first line
            
            // Position at bottom
            $pdf->SetXY(0, $pageHeight - 25);
            $pdf->Cell($pageWidth, 8, "This book is personally customized for {$customerName}", 0, 1, 'C', false);
        }
        
        // Add "Licensed to: Customer Name | Valid: Date" on EVERY page (BOLD)
        $pdf->SetFont('helvetica', 'B', 10); // Bold font
        $pdf->SetTextColor(0, 0, 0); // Black color
        
        // Position at bottom
        $pdf->SetXY(0, $pageHeight - 15);
        $footerText = "Licensed to: {$customerName} | Valid: {$validDate}";
        $pdf->Cell($pageWidth, 8, $footerText, 0, 1, 'C', false);
        
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
