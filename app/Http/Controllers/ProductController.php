<?php

namespace App\Http\Controllers;

use App\Models\Book;

class ProductController extends Controller
{
    /**
     * Display product details page
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        // Get related products (exclude current)
        $relatedProducts = Book::where('id', '!=', $id)
            ->where('category', $book->category)
            ->limit(3)
            ->get();

        return view('products.show', compact('book', 'relatedProducts'));
    }

    /**
     * Download free PDF book
     */
    public function downloadPdf($id)
    {
        $book = Book::findOrFail($id);

        // Check if book is free and has a PDF
        if (! $book->is_free || ! $book->book_pdf) {
            abort(403, 'This book is not available for free download.');
        }

        // Get file path from public/books (works for both local and production)
        $filePath = public_path('public/books/'.$book->book_pdf);

        // Check if file exists in public/books
        if (! file_exists($filePath)) {
            // Try public/books as fallback
            $filePath = public_path('books/'.$book->book_pdf);
        }

        // Check if file exists
        if (! file_exists($filePath)) {
            abort(404, 'PDF file not found.');
        }

        // Force download
        return response()->download($filePath, $book->book_pdf, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$book->book_pdf.'"',
        ]);
    }
}
