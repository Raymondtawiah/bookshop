<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public int $rating;

    public ?string $comment;

    public ?string $userEmail;

    public function __construct(int $rating, ?string $comment = null, ?string $userEmail = null)
    {
        $this->rating = $rating;
        $this->comment = $comment;
        $this->userEmail = $userEmail;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Website Feedback - Rating: '.$this->rating.'/5',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.feedback-submitted',
            with: [
                'rating' => $this->rating,
                'comment' => $this->comment,
                'userEmail' => $this->userEmail,
            ],
        );
    }
}
