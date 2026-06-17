<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\WebinarRegistration;
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
        $featuredBooks = Book::where('is_featured', true)->latest()->take(2)->get();

        // Fallback to latest books if no featured books exist
        if ($featuredBooks->count() === 0) {
            $featuredBooks = Book::latest()->take(2)->get();
        }

        return view('welcome', compact('books', 'featuredBooks'));
    }

    /**
     * Show the customer dashboard with books.
     */
    public function dashboard()
    {
        $books = Book::latest()->take(8)->get();
        $featuredBooks = Book::latest()->take(8)->get();
        $availableBooks = Book::latest()->take(4)->get();
        $recentOrders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->take(5)->get();
        $webinarRegistrations = WebinarRegistration::where('user_id', auth()->id())
            ->with('webinar')
            ->latest()
            ->take(5)
            ->get();

        // Calculate profile completeness
        $profileComplete = 0;
        $totalFields = 4;
        if (auth()->user()->name) {
            $profileComplete++;
        }
        if (auth()->user()->email) {
            $profileComplete++;
        }
        if (auth()->user()->phone) {
            $profileComplete++;
        }
        if (auth()->user()->address) {
            $profileComplete++;
        }
        $percentage = round(($profileComplete / $totalFields) * 100);

        // Use CartService to get cart data - following SOLID principles
        $cartService = app(CartService::class);
        $cartCount = $cartService->getItemCount();

        return view('dashboard', compact('books', 'featuredBooks', 'availableBooks', 'recentOrders', 'webinarRegistrations', 'percentage', 'cartCount'));
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

        if ($request->ajax || $request->input('ajax') == 1) {
            return view('components.sections.store-section', compact('books'));
        }

        if (Auth::check()) {
            return view('dashboard', array_merge(
                $this->dashboardData(),
                compact('books', 'query')
            ));
        }

        return view('welcome', compact('books', 'query'));
    }

    private function dashboardData(): array
    {
        $featuredBooks = Book::latest()->take(8)->get();
        $availableBooks = Book::latest()->take(4)->get();
        $recentOrders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->take(5)->get();
        $webinarRegistrations = WebinarRegistration::where('user_id', auth()->id())
            ->with('webinar')
            ->latest()
            ->take(5)
            ->get();

        $profileComplete = 0;
        $totalFields = 4;
        if (auth()->user()->name) {
            $profileComplete++;
        }
        if (auth()->user()->email) {
            $profileComplete++;
        }
        if (auth()->user()->phone) {
            $profileComplete++;
        }
        if (auth()->user()->address) {
            $profileComplete++;
        }
        $percentage = round(($profileComplete / $totalFields) * 100);

        $cartCount = app(CartService::class)->getItemCount();

        return compact('featuredBooks', 'availableBooks', 'recentOrders', 'webinarRegistrations', 'percentage', 'cartCount');
    }
}
