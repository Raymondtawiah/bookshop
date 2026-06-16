<?php

namespace App\Services;

use App\Mail\WebinarReminderMail;
use App\Models\WebinarSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebinarReminderService
{
    /**
     * Send automated reminders for upcoming webinars
     */
    public function sendAutomatedReminders(): void
    {
        $this->send24HourReminders();
        $this->send1HourReminders();
        $this->send15MinuteReminders();
    }

    /**
     * Send 24-hour reminders
     */
    private function send24HourReminders(): void
    {
        $webinars = WebinarSession::where('scheduled_at', '>', now())
            ->where('scheduled_at', '<=', now()->addHours(25))
            ->where('scheduled_at', '>', now()->addHours(23))
            ->where('status', 'active')
            ->get();

        foreach ($webinars as $webinar) {
            $this->sendReminderToRegistrants($webinar, '24_hours');
        }
    }

    /**
     * Send 1-hour reminders
     */
    private function send1HourReminders(): void
    {
        $webinars = WebinarSession::where('scheduled_at', '>', now())
            ->where('scheduled_at', '<=', now()->addMinutes(65))
            ->where('scheduled_at', '>', now()->addMinutes(55))
            ->where('status', 'active')
            ->get();

        foreach ($webinars as $webinar) {
            $this->sendReminderToRegistrants($webinar, '1_hour');
        }
    }

    /**
     * Send 15-minute reminders
     */
    private function send15MinuteReminders(): void
    {
        $webinars = WebinarSession::where('scheduled_at', '>', now())
            ->where('scheduled_at', '<=', now()->addMinutes(20))
            ->where('scheduled_at', '>', now()->addMinutes(10))
            ->where('status', 'active')
            ->get();

        foreach ($webinars as $webinar) {
            $this->sendReminderToRegistrants($webinar, '15_minutes');
        }
    }

    /**
     * Send reminder to all paid registrants for a webinar
     */
    private function sendReminderToRegistrants(WebinarSession $webinar, string $type): void
    {
        $registrations = $webinar->registrations()
            ->where('payment_status', 'paid')
            ->where(function ($query) {
                $query->whereNull('access_token_expires_at')
                    ->orWhere('access_token_expires_at', '>', now());
            })
            ->get();

        foreach ($registrations as $registration) {
            try {
                Mail::to($registration->email)->send(
                    new WebinarReminderMail($webinar, $registration, $type)
                );

                Log::info('Webinar reminder sent', [
                    'webinar_id' => $webinar->id,
                    'registration_id' => $registration->id,
                    'email' => $registration->email,
                    'type' => $type,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send webinar reminder', [
                    'webinar_id' => $webinar->id,
                    'registration_id' => $registration->id,
                    'email' => $registration->email,
                    'type' => $type,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Send post-webinar follow-up email
     */
    public function sendPostWebinarFollowUp(): void
    {
        $webinars = WebinarSession::where('scheduled_at', '<', now()->subHours(2))
            ->where('scheduled_at', '>', now()->subHours(26))
            ->where('status', 'active')
            ->get();

        foreach ($webinars as $webinar) {
            $this->sendFollowUpToAttendees($webinar);
        }
    }

    /**
     * Send follow-up to attendees
     */
    private function sendFollowUpToAttendees(WebinarSession $webinar): void
    {
        $attendees = $webinar->registrations()
            ->where('payment_status', 'paid')
            ->whereNotNull('joined_at')
            ->where(function ($query) {
                $query->whereNull('access_token_expires_at')
                    ->orWhere('access_token_expires_at', '>', now());
            })
            ->get();

        foreach ($attendees as $attendee) {
            try {
                Mail::to($attendee->email)->send(
                    new WebinarReminderMail($webinar, $attendee, 'post_webinar')
                );

                Log::info('Post-webinar follow-up sent', [
                    'webinar_id' => $webinar->id,
                    'registration_id' => $attendee->id,
                    'email' => $attendee->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send post-webinar follow-up', [
                    'webinar_id' => $webinar->id,
                    'registration_id' => $attendee->id,
                    'email' => $attendee->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
