@component('mail::message')
# Payment Confirmed - Coaching Session Booked!

Dear {{ $booking->name }},

Great news! Your payment has been successfully processed and your coaching session is confirmed.

## Booking Details

**Package:** {{ ucfirst($booking->package) }}
**Interview Type:** {{ $booking->interview_type }}
**Interview Date:** {{ $booking->interview_date->format('F j, Y') }}
**Interview Time:** {{ $booking->interview_time }}
**Amount Paid:** ₵{{ number_format($booking->amount, 2) }}

@if($booking->notes)
**Your Notes:** {{ $booking->notes }}
@endif

## What's Next?

1. **Check Your Email** - We've sent you a meeting link for your session
2. **Prepare** - Review any materials we'll send you before your session
3. **Join On Time** - Be ready a few minutes before your scheduled time

If you have any questions or need to reschedule, please contact us.

See you at your session!

Best regards,
The Nathaniel Gyarteng Team
@endcomponent