<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookFormService;
use App\Services\WordToPdfService;
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

        // Store directly in public/books folder
        $file->move(public_path('books'), $filename);

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

        @unlink(public_path('books/'.$filename));
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
        \Illuminate\Support\Facades\Log::info('BookController store', [
            'has book_pdfs' => $request->hasFile('book_pdfs'),
            'book_pdfs raw' => $request->file('book_pdfs'),
            'all files keys' => array_keys($request->allFiles()),
            'is_free' => $request->input('is_free'),
        ]);
        
        $bookFormService = app(BookFormService::class);
        $validated = $bookFormService->validateBookData($request);

        $pdfFiles = $request->file('book_pdfs', []);
        
        foreach ($pdfFiles as $index => $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $extension = $file->getClientOriginalExtension();
                
                if (strtolower($extension) === 'pdf') {
                    $pdfPath = $this->uploadFile($file);
                    if (!isset($validated['book_pdf']) && $pdfPath) {
                        $validated['book_pdf'] = $pdfPath;
                    }
                }
            }
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        $validated['stock'] = $validated['stock'] ?? 0;

        Book::create($validated);

        return redirect()->route('admin.books')->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $bookFormService = app(BookFormService::class);
        $validated = $bookFormService->validateBookUpdate($request);

        if ($request->hasFile('cover_image')) {
            $this->deleteFile($book->cover_image);
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        if ($request->hasFile('book_pdf')) {
            $this->deleteFile($book->book_pdf);
            $validated['book_pdf'] = $this->uploadFile($request->file('book_pdf'));
        }

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
