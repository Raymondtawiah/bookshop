<?php

namespace App\Mail;

use App\Models\CoachingBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachingMeetingLink extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public $meetingLink;

    public $meetingTime;

    public $meetingNotes;

    public function __construct(CoachingBooking $booking, $meetingLink, $meetingTime, $meetingNotes = null)
    {
        $this->booking = $booking;
        $this->meetingLink = $meetingLink;
        $this->meetingTime = $meetingTime;
        $this->meetingNotes = $meetingNotes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Coaching Session Meeting Link',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.coaching-meeting-link',
            with: [
                'booking' => $this->booking,
                'meetingLink' => $this->meetingLink,
                'meetingTime' => $this->meetingTime,
                'meetingNotes' => $this->meetingNotes,
            ],
        );
    }
}
