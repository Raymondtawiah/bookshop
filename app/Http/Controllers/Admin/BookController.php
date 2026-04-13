<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookFormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $bookFormService = app(BookFormService::class);
        $validated = $bookFormService->validateBookData($request);

        $isPdf = ($request->input('book_type') === 'pdf');

        // PDF upload
        if ($isPdf) {
            $pdfFile = $request->file('book_pdfs');
            if ($pdfFile) {
                if (strtolower($pdfFile->getClientOriginalExtension()) === 'pdf') {
                    $pdfPath = $this->uploadFile($pdfFile);
                    $validated['book_pdf'] = $pdfPath;
                }
            }
        }

        // Cover image upload
        if ($request->hasFile('cover_image')) {
            $coverFile = $request->file('cover_image');
            $validated['cover_image'] = $this->uploadFile($coverFile);
        }

        // Only set stock for cover books
        if (!$isPdf) {
            $validated['stock'] = $validated['stock'] ?? 0;
        }

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

        $isPdf = ($request->input('book_type') === 'pdf');

        // Cover image upload
        if ($request->hasFile('cover_image')) {
            $this->deleteFile($book->cover_image);
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        // PDF upload
        if ($isPdf && $request->hasFile('book_pdfs')) {
            $this->deleteFile($book->book_pdf);
            $validated['book_pdf'] = $this->uploadFile($request->file('book_pdfs'));
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