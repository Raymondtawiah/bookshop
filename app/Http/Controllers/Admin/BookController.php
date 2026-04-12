<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookFormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Upload file (image or PDF) to public storage
     */
    private function uploadFile($file)
    {
        if (! $file) {
            return null;
        }

        // Generate safe filename
        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        // Store in storage/app/public/books
        $file->storeAs('books', $filename, 'public');

        return $filename;
    }

    /**
     * Delete file from public storage
     */
    private function deleteFile($filename)
    {
        if (! $filename) {
            return;
        }

        // PROPER Laravel way
        Storage::disk('public')->delete('books/'.$filename);
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
        \Log::info('BookController store', [
            'has book_pdfs' => $request->hasFile('book_pdfs'),
            'is_free' => $request->input('is_free'),
        ]);

        $bookFormService = app(BookFormService::class);
        $validated = $bookFormService->validateBookData($request);

        // Handle multiple PDFs if provided
        $pdfFiles = $request->file('book_pdfs', []);

        foreach ($pdfFiles as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {

                if (strtolower($file->getClientOriginalExtension()) === 'pdf') {
                    $pdfPath = $this->uploadFile($file);

                    if (!isset($validated['book_pdf']) && $pdfPath) {
                        $validated['book_pdf'] = $pdfPath;
                    }
                }
            }
        }

        // Cover image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        $validated['stock'] = $validated['stock'] ?? 0;

        Book::create($validated);

        return redirect()->route('admin.books')
            ->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $bookFormService = app(BookFormService::class);
        $validated = $bookFormService->validateBookUpdate($request);

        // Replace cover image
        if ($request->hasFile('cover_image')) {
            $this->deleteFile($book->cover_image);
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        // Replace PDF
        if ($request->hasFile('book_pdf')) {
            $this->deleteFile($book->book_pdf);
            $validated['book_pdf'] = $this->uploadFile($request->file('book_pdf'));
        }

        $book->update($validated);

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