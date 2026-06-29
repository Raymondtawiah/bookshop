<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceRejected extends Notification implements ShouldQueue
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
            ->subject('Attendance Rejected')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your attendance for ' . $this->attendance->attendance_date->format('M d, Y') . ' has been rejected.')
            ->line('Reason: ' . $this->attendance->rejected_reason)
            ->line('Rejected by: ' . optional($this->attendance->approver)->name)
            ->action('View Attendance', url('/finance/attendance'))
            ->line('Please contact your administrator if you have questions.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_rejected',
            'attendance_id' => $this->attendance->id,
            'attendance_date' => $this->attendance->attendance_date,
            'rejected_reason' => $this->attendance->rejected_reason,
            'rejected_by' => optional($this->attendance->approver)->name,
            'message' => 'Your attendance was rejected: ' . $this->attendance->rejected_reason,
        ];
    }
}
