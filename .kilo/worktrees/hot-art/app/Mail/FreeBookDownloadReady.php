<?php

namespace App\Mail;

use App\Models\FreeBookLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FreeBookDownloadReady extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    public $book;

    public function __construct(FreeBookLead $lead)
    {
        $this->lead = $lead;
        $this->book = $lead->book;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Free Book: '.$this->lead->book_title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.free-book-download',
            with: [
                'lead' => $this->lead,
                'book' => $this->book,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
