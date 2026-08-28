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

    public function __construct(string $senderName, string $message, string $chatUrl)
    {
        $this->senderName = $senderName;
        $this->message = $message;
        $this->chatUrl = $chatUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Chat Message',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.chat-notification',
            with: [
                'senderName' => $this->senderName,
                'message' => $this->message,
                'chatUrl' => $this->chatUrl,
            ],
        );
    }
}
