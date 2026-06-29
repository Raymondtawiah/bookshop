<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public \App\Models\User $staff, public \App\Models\Attendance $attendance)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Attendance Request')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->staff->name . ' has submitted attendance at ' . optional($this->attendance->clock_in)->format('H:i') . '.')
            ->action('Review Attendance', url('/admin/staff'))
            ->line('Please review the pending attendance request.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_submitted',
            'user_id' => $this->staff->id,
            'user_name' => $this->staff->name,
            'attendance_id' => $this->attendance->id,
            'clock_in' => $this->attendance->clock_in,
            'message' => $this->staff->name . ' submitted attendance at ' . optional($this->attendance->clock_in)->format('H:i') . '.',
        ];
    }
}
