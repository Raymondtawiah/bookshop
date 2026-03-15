<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        if (!$book->is_free || !$book->book_pdf) {
            abort(403, 'This book is not available for free download.');
        }

        $filePath = public_path('books/' . $book->book_pdf);

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'PDF file not found.');
        }

        return response()->download($filePath);
    }
}