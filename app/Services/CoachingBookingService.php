<?php

namespace App\Services;

use App\Models\CoachingBooking;

class CoachingBookingService
{
    public function getStatusLabel(CoachingBooking $booking): string
    {
        if ($booking->status === 'cancelled') {
            return 'cancelled';
        }

        if ($booking->meeting_link || $booking->meeting_time) {
            return 'completed';
        }

        return $booking->status ?? 'pending';
    }

    public function getStatusClass(CoachingBooking $booking): string
    {
        $status = $this->getStatusLabel($booking);

        return match ($status) {
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }

    public function hasMeetingLinkBeenSent(CoachingBooking $booking): bool
    {
        return ! empty($booking->meeting_link);
    }

    public function getMeetingDetails(CoachingBooking $booking): ?array
    {
        if (! $this->hasMeetingLinkBeenSent($booking) && ! $booking->meeting_time) {
            return null;
        }

        return [
            'link' => $booking->meeting_link,
            'time' => $booking->meeting_time,
            'notes' => $booking->meeting_notes,
        ];
    }
}
