<?php

namespace App\Http\Controllers;

use App\Mail\FreeBookDownloadReady;
use App\Models\Book;
use App\Services\FreeBookDownloadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected FreeBookDownloadService $freeBookDownloadService;

    public function __construct(FreeBookDownloadService $freeBookDownloadService)
    {
        $this->freeBookDownloadService = $freeBookDownloadService;
    }

    /**
     * Display product details page
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        // Get related products (exclude current)
        $relatedProducts = Book::where('id', '!=', $id)
            ->where('category', $book->category)
            ->limit(3)
            ->get();

        return view('products.show', compact('book', 'relatedProducts'));
    }

    /**
     * Download free PDF book
     */
    public function downloadPdf($id)
    {
        $book = Book::findOrFail($id);

        // Check if book is free and has a PDF
        if (! $book->is_free || ! $book->book_pdf) {
            abort(403, 'This book is not available for free download.');
        }

        // Try serving from storage (non-public)
        $filePath = storage_path('app/books/'.$book->book_pdf);
        if (! file_exists($filePath)) {
            // Fallback to public/books for legacy files
            $filePath = public_path('public/books/'.$book->book_pdf);
            if (! file_exists($filePath)) {
                $filePath = public_path('books/'.$book->book_pdf);
                if (! file_exists($filePath)) {
                    abort(404, 'PDF file not found.');
                }
            }
        }

        // Force download
        return response()->download($filePath, $book->book_pdf, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$book->book_pdf.'"',
        ]);
    }

    /**
     * Create a lead record for free book download
     */
    public function createLead(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'book_id' => 'required|integer|exists:books,id',
        ]);

        $book = Book::findOrFail($request->book_id);

        if (! $book->is_free || ! $book->book_pdf) {
            return response()->json([
                'success' => false,
                'message' => 'This book is not available for free download.',
            ], 403);
        }

        $lead = $this->freeBookDownloadService->createLead(
            $request->full_name,
            $request->email,
            $book->id
        );

        // Send confirmation email with download link
        try {
            Mail::to($lead->email)->send(new FreeBookDownloadReady($lead));
            $lead->update(['notified_at' => now()]);
        } catch (\Exception $e) {
            Log::error('Failed to send free book download email', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your download link has been sent to your email.',
            'token' => $lead->download_token,
        ]);
    }

    /**
     * Download free book by token
     */
    public function downloadByToken($token)
    {
        $lead = $this->freeBookDownloadService->fulfillDownload($token);
        $filePath = $this->freeBookDownloadService->getDownloadPath($lead);

        return response()->download($filePath, $lead->book->book_pdf, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$lead->book->book_pdf.'"',
        ]);
    }
}
