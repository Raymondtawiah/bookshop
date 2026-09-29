<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChatNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $senderName;

    public string $message;

    public string $chatUrl;

    public ?string $senderEmail = null;

    private ?string $mailSubject = null;

    public function __construct(string $senderName, string $message, string $chatUrl, ?string $senderEmail = null, ?string $subject = null)
    {
        $this->senderName = $senderName;
        $this->message = $message;
        $this->chatUrl = $chatUrl;
        $this->senderEmail = $senderEmail;
        $this->mailSubject = $subject;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject ?? 'New Chat Message',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.chat-notification',
            with: [
                'senderName' => $this->senderName,
                'senderEmail' => $this->senderEmail,
                'chatMessage' => $this->message,
                'chatUrl' => $this->chatUrl,
            ],
        );
    }
}
