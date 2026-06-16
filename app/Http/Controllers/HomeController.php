<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\CartService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page with books.
     */
    public function index()
    {
        $books = \App\Models\Book::latest()->take(8)->get();
        return view('welcome', compact('books'));
    }

    /**
     * Show the customer dashboard with books.
     */
    public function dashboard()
    {
        $books = \App\Models\Book::latest()->take(8)->get();
        
        // Use CartService to get cart data - following SOLID principles
        $cartService = app(CartService::class);
        $cartCount = $cartService->getItemCount();
        
        return view('dashboard', compact('books', 'cartCount'));
    }
}
