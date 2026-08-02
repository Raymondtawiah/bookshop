<?php

namespace App\Services;

use App\Models\Book;
use App\Models\FreeBookLead;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FreeBookDownloadService
{
    public function generateToken(): string
    {
        return Str::random(60);
    }

    public function createLead(string $fullName, string $email, int $bookId): FreeBookLead
    {
        $book = Book::findOrFail($bookId);

        if (! $book->is_free || ! $book->book_pdf) {
            abort(403, 'This book is not available for free download.');
        }

        $lead = FreeBookLead::create([
            'full_name' => $fullName,
            'email' => $email,
            'book_id' => $book->id,
            'book_title' => $book->title,
            'download_token' => $this->generateToken(),
        ]);

        Log::info('Free book lead created', [
            'lead_id' => $lead->id,
            'book_id' => $book->id,
            'email' => $email,
        ]);

        NotificationService::newFreeBookDownload($lead);

        return $lead;
    }

    public function fulfillDownload(string $token): FreeBookLead
    {
        $lead = FreeBookLead::where('download_token', $token)->firstOrFail();

        if (! $lead->downloaded_at) {
            $lead->update(['downloaded_at' => now()]);

            Log::info('Free book downloaded', [
                'lead_id' => $lead->id,
                'book_id' => $lead->book_id,
            ]);
        }

        return $lead;
    }

    public function getDownloadPath(FreeBookLead $lead): string
    {
        $book = $lead->book;

        $filePath = storage_path('app/books/'.$book->book_pdf);
        if (! file_exists($filePath)) {
            $filePath = public_path('public/books/'.$book->book_pdf);
            if (! file_exists($filePath)) {
                $filePath = public_path('books/'.$book->book_pdf);
                if (! file_exists($filePath)) {
                    abort(404, 'PDF file not found.');
                }
            }
        }

        return $filePath;
    }
}
