<?php

namespace App\Console\Commands;

use App\Mail\CoachingMeetingReminder;
use App\Models\CoachingBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCoachingReminders extends Command
{
    protected $signature = 'coaching:send-reminders';

    protected $description = 'Send automatic reminders to customers before their coaching sessions';

    public function handle(): int
    {
        $now = now();

        $reminderTimes = [30, 10, 5]; // minutes before meeting to send reminders

        foreach ($reminderTimes as $minutesBefore) {
            $targetTime = $now->copy()->addMinutes($minutesBefore);
            
            $bookings = CoachingBooking::whereNotNull('meeting_time')
                ->where('meeting_time', '>=', $targetTime->copy()->subMinutes(1))
                ->where('meeting_time', '<=', $targetTime->copy()->addMinutes(1))
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'completed')
                ->where(function ($query) use ($minutesBefore) {
                    $query->whereNull('reminder_sent_at')
                        ->orWhere('reminder_sent_at', '<', now()->subMinutes($minutesBefore * 2));
                })
                ->get();

            foreach ($bookings as $booking) {
                try {
                    Mail::to($booking->email)->send(new CoachingMeetingReminder($booking, $minutesBefore));
                    
                    $booking->update(['reminder_sent_at' => now()]);
                    
                    $this->info("Sent {$minutesBefore}-minute reminder to {$booking->email}");
                } catch (\Exception $e) {
                    $this->error("Failed to send reminder to {$booking->email}: " . $e->getMessage());
                }
            }
        }

        $this->info('Reminders check completed.');
        return Command::SUCCESS;
    }
}