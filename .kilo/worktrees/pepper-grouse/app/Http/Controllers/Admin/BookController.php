<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    private function uploadFile($file)
    {
        if (!$file) {
            return null;
        }

        $booksDir = public_path('books');
        
        // Create books directory if it doesn't exist
        if (!is_dir($booksDir)) {
            mkdir($booksDir, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($booksDir, $filename);

        return $filename;
    }

    private function deleteFile($filename)
    {
        if (!$filename) {
            return;
        }

        $path = public_path('books/' . $filename);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
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
            'price' => 'required|numeric|min:0',
        ]);

        // Upload cover image
        $coverImage = null;
        if ($request->hasFile('cover_image')) {
            $coverImage = $this->uploadFile($request->file('cover_image'));
        }

        // Upload PDF if PDF book type
        $bookPdf = null;
        if ($request->input('book_type') === 'pdf' && $request->hasFile('book_pdfs')) {
            $bookPdf = $this->uploadFile($request->file('book_pdfs'));
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'price' => $request->price,
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
            'price' => $request->price,
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
        if ($request->hasFile('book_pdfs')) {
            $this->deleteFile($book->book_pdf);
            $data['book_pdf'] = $this->uploadFile($request->file('book_pdfs'));
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
}