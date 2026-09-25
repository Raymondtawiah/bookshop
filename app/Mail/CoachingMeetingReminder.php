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
        public int $minutesUntil,
        public $reminderDatetime,
        public $meetingLink
    ) {}

    public function envelope(): Envelope
    {
        $minutes = (int) $this->minutesUntil;
        if ($minutes < 60) {
            $timeText = $minutes . ' minute' . ($minutes !== 1 ? 's' : '');
        } elseif ($minutes < 1440) {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            if ($mins === 0) {
                $timeText = $hours . ' hour' . ($hours !== 1 ? 's' : '');
            } else {
                $timeText = $hours . ' hour' . ($hours !== 1 ? 's' : '') . ' ' . $mins . ' minute' . ($mins !== 1 ? 's' : '');
            }
        } else {
            $days = floor($minutes / 1440);
            $hours = floor(($minutes % 1440) / 60);
            $timeText = $days . ' day' . ($days !== 1 ? 's' : '') . ' ' . $hours . ' hour' . ($hours !== 1 ? 's' : '');
        }

        return new Envelope(
            subject: "Reminder: Your Coaching Session in {$timeText}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.coaching-reminder',
            with: [
                'booking' => $this->booking,
                'minutesUntil' => $this->minutesUntil,
                'reminderDatetime' => $this->reminderDatetime,
                'meetingLink' => $this->meetingLink,
            ],
        );
    }
}
