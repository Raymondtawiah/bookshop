<?php

namespace App\Contracts;

use App\Models\Book;
use App\Models\Order;

/**
 * Interface for PDF Generation
 *
 * This follows the Open/Closed Principle (OCP) - open for extension, closed for modification.
 * New PDF generation methods can be added without modifying existing code.
 */
interface PdfGeneratorInterface
{
    /**
     * Generate a PDF with customer name watermark
     *
     * @param  Book  $book  The book to generate PDF from
     * @param  Order  $order  The order containing customer details
     * @return string Path to the generated PDF file
     */
    public function generateWithWatermark(Book $book, Order $order): string;

    /**
     * Generate PDF from text content with customer name at bottom
     *
     * @param  string  $content  The text content to convert to PDF
     * @param  Order  $order  The order containing customer details
     * @param  string  $title  Optional title for the PDF
     * @return string Path to the generated PDF file
     */
    public function generateFromText(string $content, Order $order, string $title = 'Document'): string;

    /**
     * Generate a simple PDF without watermark
     *
     * @param  Book  $book  The book to generate PDF from
     * @return string Path to the generated PDF file
     */
    public function generateSimple(Book $book): string;

    /**
     * Check if the book has a source PDF file
     */
    public function hasSourcePdf(Book $book): bool;

    /**
     * Check if the book has a Word file
     */
    public function hasWordFile(Book $book): bool;

    /**
     * Generate PDF from Word document
     *
     * @param  Book  $book  The book containing the Word file
     * @param  Order  $order  The order containing customer details
     * @return array ['success' => bool, 'message' => string, 'pdf_path' => string|null]
     */
    public function generateFromWord(Book $book, Order $order): array;
}
