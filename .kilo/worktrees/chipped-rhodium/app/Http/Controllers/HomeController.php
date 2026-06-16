<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the home page with books.
     */
    public function index()
    {
        $books = Book::latest()->take(8)->get();

        return view('welcome', compact('books'));
    }

    /**
     * Show the customer dashboard with books.
     */
    public function dashboard()
    {
        $books = Book::latest()->take(8)->get();

        // Use CartService to get cart data - following SOLID principles
        $cartService = app(CartService::class);
        $cartCount = $cartService->getItemCount();

        return view('dashboard', compact('books', 'cartCount'));
    }

    /**
     * Search books.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $books = Book::where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->latest()
            ->get();

        if (Auth::check()) {
            $cartService = app(CartService::class);
            $cartCount = $cartService->getItemCount();
            return view('dashboard', compact('books', 'query', 'cartCount'));
        }

        return view('welcome', compact('books', 'query'));
    }
}
