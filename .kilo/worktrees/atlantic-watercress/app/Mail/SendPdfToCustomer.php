<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPdfToCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $pdfPaths;
    public $orderId;

    public function __construct($user, $pdfPaths, $orderId)
    {
        $this->user = $user;
        $this->pdfPaths = $pdfPaths;
        $this->orderId = $orderId;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Visa Resource PDF - Order #'.$this->orderId,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pdf-delivery',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        foreach ($this->pdfPaths as $pdf) {
            if (file_exists($pdf['path'])) {
                $attachments[] = Attachment::fromPath($pdf['path'])
                    ->as($pdf['filename'])
                    ->withMime('application/pdf');
            }
        }
        
        return $attachments;
    }
}
