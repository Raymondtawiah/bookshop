@component('mail::message')
# Coaching Session Confirmed!

Dear {{ $booking->name }},

Your coaching session has been successfully booked! We're excited to help you prepare for your interview.

## Booking Details

**Interview Type:** {{ $booking->interview_type }}
**Date:** {{ $booking->interview_date->format('F j, Y') }}
**Time:** {{ $booking->interview_time }}
**Package:** {{ ucfirst($booking->package) }}

@if($booking->notes)
**Notes:** {{ $booking->notes }}
@endif

## What's Next?

1. **Confirm Your Spot** - We'll send you a payment link shortly
2. **Join Your Session** - You'll receive a meeting link before your session
3. **Get Prepared** - We'll send you some materials to review before our session

If you have any questions, please don't hesitate to reach out.

Best regards,
The Nathaniel Gyarteng Team
@endcomponent
