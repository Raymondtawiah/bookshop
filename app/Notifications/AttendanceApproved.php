<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public \App\Models\Attendance $attendance)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Attendance Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your attendance has been approved by ' . optional($this->attendance->approver)->name . '.')
            ->line('Approved at: ' . optional($this->attendance->approved_at)->format('M d, Y H:i') . '.')
            ->action('View Attendance', url('/finance/attendance'))
            ->line('Thank you!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_approved',
            'attendance_id' => $this->attendance->id,
            'attendance_date' => $this->attendance->attendance_date,
            'approved_by' => optional($this->attendance->approver)->name,
            'approved_at' => $this->attendance->approved_at?->format('M d, Y H:i'),
            'message' => 'Your attendance has been approved.',
        ];
    }
}
