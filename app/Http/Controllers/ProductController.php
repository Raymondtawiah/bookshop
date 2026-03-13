<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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
}