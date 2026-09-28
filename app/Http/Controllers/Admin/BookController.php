<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private function uploadFile($file)
    {
        if (! $file) {
            return null;
        }

        $booksDir = public_path('books');

        // Create books directory if it doesn't exist
        if (! is_dir($booksDir)) {
            mkdir($booksDir, 0755, true);
        }

        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move($booksDir, $filename);

        return $filename;
    }

    private function deleteFile($filename)
    {
        if (! $filename) {
            return;
        }

        $path = public_path('books/'.$filename);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function index(Request $request)
    {
        $query = $request->input('q');

        if ($query) {
            $books = Book::where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%")
                ->orWhere('published_year', $query)
                ->latest()
                ->paginate(10);
        } else {
            $books = Book::latest()->paginate(10);
        }

        $book = Book::first();
        $orders = \App\Models\Order::latest()->get();
        $bonusSessions = collect([]);

        return view('admin.books.index', compact('books', 'book', 'orders', 'bonusSessions'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function createPdf()
    {
        return view('admin.books.create-pdf');
    }

    public function store(Request $request)
    {
        // Simple validation - just required fields
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
        ]);

        // Upload cover image
        $coverImage = null;
        if ($request->hasFile('cover_image')) {
            $coverImage = $this->uploadFile($request->file('cover_image'));
        }

        // Upload PDF if PDF book type
        $bookPdf = null;
        if ($request->hasFile('book_pdf')) {
            $bookPdf = $this->uploadFile($request->file('book_pdf'));
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'isbn' => $request->isbn,
            'pages' => $request->pages,
            'published_year' => $request->published_year,
            'cover_image' => $coverImage,
            'book_pdf' => $bookPdf,
            'is_free' => $request->boolean('is_free'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.books')
            ->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        // Simple validation
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'isbn' => $request->isbn,
            'pages' => $request->pages,
            'published_year' => $request->published_year,
            'is_featured' => $request->boolean('is_featured'),
        ];

        // Update cover image if new one uploaded
        if ($request->hasFile('cover_image')) {
            $this->deleteFile($book->cover_image);
            $data['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        // Update PDF if new one uploaded
        if ($request->hasFile('book_pdf')) {
            $this->deleteFile($book->book_pdf);
            $data['book_pdf'] = $this->uploadFile($request->file('book_pdf'));
        }

        $book->update($data);

        return redirect()->route('admin.books')
            ->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $this->deleteFile($book->cover_image);
        $this->deleteFile($book->book_pdf);

        $book->delete();

        return redirect()->route('admin.books')
            ->with('success', 'Book deleted successfully!');
    }

    public function bookings(Request $request)
    {
        $query = \App\Models\Order::query()
            ->whereNotNull('booking_date')
            ->where('booking_date', '!=', '')
            ->latest('booking_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(20)->appends($request->except('page'));

        return view('admin.bookings.index', compact('bookings'));
    }

    public function sendBookingReminder(\App\Models\Order $order)
    {
        if (! $order->booking_date || ! $order->booking_time) {
            return back()->with('error', 'This booking does not have a valid date and time.');
        }

        try {
            \Mail::to($order->email)->send(new \App\Mail\BookingReminderMail($order));

            return back()->with('success', 'Booking reminder sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reminder: '.$e->getMessage());
        }
    }

    public function bookingSettings()
    {
        $bookingTimes = \App\Models\SiteSetting::get('booking_times');
        $decodedTimes = is_string($bookingTimes) ? json_decode($bookingTimes, true) : $bookingTimes;
        $bookingZoomLink = \App\Models\SiteSetting::get('booking_zoom_link');

        return view('admin.books.booking-settings', [
            'bookingTimes' => is_array($decodedTimes) ? $decodedTimes : [],
            'bookingZoomLink' => $bookingZoomLink,
        ]);
    }

    public function updateBookingSettings(Request $request)
    {
        $request->validate([
            'booking_times' => 'nullable|array|min:1|max:10',
            'booking_times.*' => 'string|max:20',
            'booking_zoom_link' => 'nullable|url|max:255',
        ]);

        \App\Models\SiteSetting::set('booking_times', json_encode(array_values(array_filter($request->input('booking_times', [])))));
        \App\Models\SiteSetting::set('booking_zoom_link', $request->input('booking_zoom_link'));

        return redirect()->route('admin.bookingSettings')->with('success', 'Booking settings updated successfully.');
    }
}
