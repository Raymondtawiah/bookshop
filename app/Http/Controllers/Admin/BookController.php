<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
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

        // Store in public/storage folder (not storage/app/public)
        $path = $file->storeAs('books', $filename, 'public');

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

        // Delete from public disk
        Storage::disk('public')->delete('books/'.$filename);
    }

    /**
     * Generate public URL
     */
    private function fileUrl($filename)
    {
        if (! $filename) {
            return null;
        }

        return Storage::disk('public')->url('books/'.$filename);
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
            'book_pdfs' => 'nullable|array',
            'book_pdfs.*' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        $pdfFiles = $request->file('book_pdfs', []);
        
        // Handle multiple PDF/Word files - only process reviewed files
        $reviewedFiles = $request->input('reviewed_files', []);
        
        if (!empty($reviewedFiles)) {
            $wordService = app(WordToPdfService::class);
            
            foreach ($pdfFiles as $index => $file) {
                // Only process if this file is marked as reviewed
                if (in_array($index, $reviewedFiles) && $file) {
                    $extension = $file->getClientOriginalExtension();
                    
                    if (in_array($extension, ['doc', 'docx'])) {
                        $pdfPath = $wordService->convertToPdf($file, $validated['title'] ?? 'document');
                        if ($pdfPath) {
                            // Store as book_pdf (single for now - can be extended for multiple)
                            if (!isset($validated['book_pdf'])) {
                                $validated['book_pdf'] = $pdfPath;
                            }
                        }
                    } elseif ($extension === 'pdf') {
                        $pdfPath = $this->uploadFile($file);
                        if (!isset($validated['book_pdf']) && $pdfPath) {
                            $validated['book_pdf'] = $pdfPath;
                        }
                    }
                }
            }
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadFile($request->file('cover_image'));
        }

        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['is_free'] = $request->boolean('is_free');

        Book::create($validated);

        return redirect()->route('admin.books')->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
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
