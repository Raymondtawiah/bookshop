<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Upload file (image or PDF) to public_html/books
     */
    private function uploadFile($file)
    {
        if (!$file) return null;

        // Safer filename
        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        // Hostinger public folder
        $directory = $_SERVER['DOCUMENT_ROOT'].'/books';

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $file->move($directory, $filename);

        return $filename;
    }

    /**
     * Delete file from public_html/books
     */
    private function deleteFile($filename)
    {
        if (!$filename) return;

        $path = $_SERVER['DOCUMENT_ROOT'].'/books/'.$filename;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Generate public URL
     */
    private function fileUrl($filename)
    {
        if (!$filename) return null;

        return asset('books/'.$filename);
    }

    public function index()
    {
        $books = Book::latest()->paginate(10);

        foreach ($books as $book) {
            $book->cover_image_url = $this->fileUrl($book->cover_image);
            $book->book_pdf_url = $this->fileUrl($book->book_pdf);
        }

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'book_pdf' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        if ($request->hasFile('book_pdf')) {
            $validated['book_pdf'] = $this->uploadFile($request->file('book_pdf'));
        }

        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['is_free'] = $request->boolean('is_free');

        Book::create($validated);

        return redirect()->route('admin.books')->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        $book->cover_image_url = $this->fileUrl($book->cover_image);
        $book->book_pdf_url = $this->fileUrl($book->book_pdf);

        return view('admin.books.edit', compact('book'));
    }

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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'book_pdf' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('cover_image')) {
            $this->deleteFile($book->cover_image);
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        if ($request->hasFile('book_pdf')) {
            $this->deleteFile($book->book_pdf);
            $validated['book_pdf'] = $this->uploadFile($request->file('book_pdf'));
        }

        $validated['is_free'] = $request->boolean('is_free');

        $book->update($validated);

        return redirect()->route('admin.books')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $this->deleteFile($book->cover_image);
        $this->deleteFile($book->book_pdf);

        $book->delete();

        return redirect()->route('admin.books')->with('success', 'Book deleted successfully!');
    }
}