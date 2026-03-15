<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Upload image to public/books directory
     */
    private function uploadImage($file)
    {
        if (!$file) return null;
        
        // Create a unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Ensure directory exists
        $directory = base_path('public/books');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Save image to public/books
        $file->move($directory, $filename);
        
        // Return only the filename for database storage
        return $filename;
    }

    /**
     * Upload PDF to public/books directory
     */
    private function uploadPdf($file)
    {
        if (!$file) return null;
        
        // Create a unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Ensure directory exists
        $directory = base_path('public/books');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Save PDF to public/books
        $file->move($directory, $filename);
        
        // Return only the filename for database storage
        return $filename;
    }

    /**
     * Delete image from public/books directory
     */
    private function deleteImage($filename)
    {
        if ($filename && file_exists(base_path('public/books') . '/' . $filename)) {
            unlink(base_path('public/books') . '/' . $filename);
        }
    }

    /**
     * Delete PDF from public/books directory
     */
    private function deletePdf($filename)
    {
        if ($filename && file_exists(base_path('public/books') . '/' . $filename)) {
            unlink(base_path('public/books') . '/' . $filename);
        }
    }

    /**
     * Display a listing of books.
     */
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('admin.books.create');
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:2100',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'book_pdf' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        if ($request->hasFile('book_pdf')) {
            $validated['book_pdf'] = $this->uploadPdf($request->file('book_pdf'));
        }

        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['is_free'] = $request->boolean('is_free');

        Book::create($validated);

        return redirect()->route('admin.books')->with('success', 'Book added successfully!');
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:2100',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'book_pdf' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            $this->deleteImage($book->cover_image);
            $book->cover_image = $this->uploadImage($request->file('cover_image'));
        }

        if ($request->hasFile('book_pdf')) {
            // Delete old PDF if exists
            $this->deletePdf($book->book_pdf);
            $book->book_pdf = $this->uploadPdf($request->file('book_pdf'));
        }

        $validated['is_free'] = $request->boolean('is_free');

        $book->update($validated);

        return redirect()->route('admin.books')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book)
    {
        $this->deleteImage($book->cover_image);
        $this->deletePdf($book->book_pdf);
        
        $book->delete();

        return redirect()->route('admin.books')->with('success', 'Book deleted successfully!');
    }
}
