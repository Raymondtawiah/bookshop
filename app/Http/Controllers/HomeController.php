<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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
        return view('dashboard', compact('books'));
    }
}
