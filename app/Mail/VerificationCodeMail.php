<?php

namespace App\Mail;

use App\Models\VerificationCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public VerificationCode $verificationCode
    ) {}

    public function envelope(): Envelope
    {
        $type = $this->verificationCode->type === 'login' 
            ? 'Login Verification Code' 
            : 'Password Reset Verification Code';

        return new Envelope(
            subject: $type,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verification-code',
        );
    }
}
