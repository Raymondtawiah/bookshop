<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachingMeetingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $booking,
        public int $minutesUntil
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reminder: Your Coaching Session in {$this->minutesUntil} minutes",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.coaching-reminder',
            with: [
                'booking' => $this->booking,
                'minutesUntil' => $this->minutesUntil,
            ],
        );
    }
}