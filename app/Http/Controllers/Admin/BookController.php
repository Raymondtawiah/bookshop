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

    public function store(Request $request)
    {
        // DEBUG
        Log::info('Upload Debug', [
            'public_path' => public_path('books'),
            'hasFile' => $request->hasFile('cover_image'),
            'book_dir_exists' => is_dir(public_path('books')),
            'book_dir_writable' => is_writable(public_path('books')),
        ]);

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
            Log::info('Uploaded cover', ['coverImage' => $coverImage]);
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
            'category' => $request->category,
            'isbn' => $request->isbn,
            'pages' => $request->pages,
            'published_year' => $request->published_year,
            'cover_image' => $coverImage,
            'book_pdf' => $bookPdf,
            'is_free' => $request->boolean('is_free'),
            'stock' => $request->input('stock', 0),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        $msg = 'Book added! Cover: ' . ($coverImage ?? 'none') . ' | Dir: ' . public_path('books');
        
        return redirect()->route('admin.books')
            ->with('success', $msg);
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
            'category' => $request->category,
            'isbn' => $request->isbn,
            'pages' => $request->pages,
            'published_year' => $request->published_year,
            'stock' => $request->stock,
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