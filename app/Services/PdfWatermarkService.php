<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use TCPDF;

class PdfWatermarkService
{
    /**
     * Add a watermark to each page of a PDF with the customer's name.
     *
     * @param string $sourcePdfPath Path to the source PDF file
     * @param string $customerName Name to add as watermark
     * @param string $outputFileName Name for the output file
     * @return string Path to the generated watermarked PDF
     */
    public function addWatermark(string $sourcePdfPath, string $customerName, string $outputFileName): string
    {
        // Get full paths
        $fullSourcePath = Storage::disk('public')->path($sourcePdfPath);
        
        // Create output directory if it doesn't exist
        $outputDir = storage_path('app/public/books/personalized');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
        
        $outputPath = $outputDir . '/' . $outputFileName;
        
        // Get the original PDF page count
        $pdf = new TCPDF();
        $pageCount = $pdf->setSourceFile($fullSourcePath);
        
        // Create new PDF
        $watermarkedPdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', true);
        $watermarkedPdf->SetCreator('Bookshop');
        $watermarkedPdf->SetAuthor('Bookshop');
        $watermarkedPdf->SetTitle('Personalized Book');
        
        // Add watermark to each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $watermarkedPdf->AddPage();
            
            // Import the page from source PDF
            $templateId = $watermarkedPdf->setSourceFile($fullSourcePath);
            $watermarkedPdf->useTemplate($watermarkedPdf->importPage($pageNo), 0, 0, 210, 297);
            
            // Add watermark text
            $this->addWatermarkText($watermarkedPdf, $customerName);
        }
        
        // Output the watermarked PDF
        $watermarkedPdf->Output($outputPath, 'F');
        
        // Return the relative path for storage
        return 'books/personalized/' . $outputFileName;
    }
    
    /**
     * Add watermark text to the current page.
     *
     * @param TCPDF $pdf The PDF object
     * @param string $name The customer name
     */
    private function addWatermarkText(TCPDF $pdf, string $name): void
    {
        // Set transparency
        $pdf->SetAlpha(0.3);
        
        // Set font
        $pdf->SetFont('helvetica', 'B', 60);
        
        // Get page dimensions
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();
        
        // Calculate position for center diagonal text
        $text = "Personalized for: {$name}";
        
        // Save state
        $pdf->saveState();
        
        // Rotate and add diagonal watermark
        $pdf->StartTransform();
        $pdf->Rotate(45, $pageWidth / 2, $pageHeight / 2);
        
        // Add text at center
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 0, $text, 0, true, 'C', false);
        
        // End transformation
        $pdf->StopTransform();
        
        // Add smaller watermark at bottom
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->SetTextColor(180, 180, 180);
        $pdf->SetAlpha(0.5);
        
        // Add smaller watermark in bottom right corner
        $pdf->SetXY($pageWidth - 80, $pageHeight - 20);
        $pdf->Cell(70, 5, "Personalized for: {$name}", 0, 0, 'R');
        
        // Restore state
        $pdf->restoreState();
    }
    
    /**
     * Generate a unique filename for the personalized PDF.
     *
     * @param int $orderId The order ID
     * @param string $customerName The customer name
     * @return string
     */
    public function generateFileName(int $orderId, string $customerName): string
    {
        $sanitizedName = preg_replace('/[^a-zA-Z0-9]/', '_', $customerName);
        return "book_order_{$orderId}_{$sanitizedName}_" . time() . '.pdf';
    }
    
    /**
     * Delete a personalized PDF file.
     *
     * @param string $filePath Path to the file
     * @return bool
     */
    public function deleteFile(string $filePath): bool
    {
        return Storage::disk('public')->delete($filePath);
    }
    
    /**
     * Check if a PDF file exists.
     *
     * @param string $filePath Path to the file
     * @return bool
     */
    public function fileExists(string $filePath): bool
    {
        return Storage::disk('public')->exists($filePath);
    }
}
