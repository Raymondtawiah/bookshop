<?php

namespace App\Mail;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public $uploadedPdfPath;

    public $uploadedPdfName;

    public function __construct(Order $order, $uploadedPdfPath = null, $uploadedPdfName = null)
    {
        $this->order = $order;
        $this->uploadedPdfPath = $uploadedPdfPath;
        $this->uploadedPdfName = $uploadedPdfName;
    }

    public function envelope(): Envelope
    {
        $orderItems = $this->order->order_items ?? collect([]);
        $firstItem = $orderItems->first();
        $bookTitle = 'Your Book';

        if ($firstItem) {
            $productName = is_array($firstItem) ? ($firstItem['product_name'] ?? null) : ($firstItem->product_name ?? null);
            if ($productName) {
                $bookTitle = $productName;
            }
        }

        return new Envelope(
            subject: 'Your Book Offer: '.$bookTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.book-offer',
            with: [
                'order' => $this->order,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->uploadedPdfPath && $this->uploadedPdfName && file_exists($this->uploadedPdfPath)) {
            $attachments[] = Attachment::fromPath($this->uploadedPdfPath)
                ->as($this->uploadedPdfName)
                ->withMime('application/pdf');
        }

        $orderItems = $this->order->order_items ?? collect([]);

        foreach ($orderItems as $item) {
            $bookId = is_array($item) ? ($item['book_id'] ?? null) : ($item->book_id ?? null);

            if ($bookId) {
                $book = Book::find($bookId);

                if ($book && $book->hasBookPdf() && ! empty($book->book_pdf)) {
                    $pdfPath = public_path('books/'.$book->book_pdf);

                    if (file_exists($pdfPath)) {
                        $attachments[] = Attachment::fromPath($pdfPath)
                            ->as($book->title.'.pdf')
                            ->withMime('application/pdf');
                    }
                }
            }
        }

        return $attachments;
    }
}
