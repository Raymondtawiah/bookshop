<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookFormService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Upload file directly to public/books
     */
    private function uploadFile($file)
    {
        if (!$file) {
            return null;
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // SAVE DIRECTLY TO public/books
        $file->move(public_path('books'), $filename);

        return $filename;
    }

    /**
     * Delete file from public/books
     */
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
        \Log::info('Book store', [
            'has_files' => $request->hasFile('cover_image'),
        ]);

        $bookFormService = app(BookFormService::class);
        $validated = $bookFormService->validateBookData($request);

        // PDF upload
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

        if ($request->hasFile('cover_image')) {
            $this->deleteFile($book->cover_image);
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

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