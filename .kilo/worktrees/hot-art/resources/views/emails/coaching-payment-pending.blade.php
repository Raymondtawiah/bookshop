@component('mail::message')
# Payment Pending - Complete Your Coaching Session Booking

Hello {{ $booking->name }},

Thank you for registering for our coaching session.

Your registration is currently pending because payment has not been completed yet. Please complete your payment to secure your spot.

@if($deadlineDatetime)
Payment Deadline: {{ \Carbon\Carbon::parse($deadlineDatetime)->format('l, F j, Y \a\t g:i A') }}

Please make sure to complete your payment before this deadline.
@endif

[Complete Your Payment]({{ $paymentLink }})

We look forward to seeing you.

Thanks,<br>
The Coaching Team
@endcomponent  