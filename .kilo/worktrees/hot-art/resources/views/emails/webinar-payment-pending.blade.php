@component('mail::message')
# Payment Pending - Complete Your Webinar Registration

Hello {{ $registration->full_name }},

Thank you for registering for our webinar: {{ $registration->webinar->title }}.

Your registration is currently pending because payment has not been completed yet. Please complete your payment to secure your spot.

@if($reminderDate)
Please complete your payment by: {{ \Illuminate\Support\Carbon::parse($reminderDate)->format('F j, Y') }}
@endif

[Complete Your Payment]({{ $paymentLink }})

We look forward to seeing you at the webinar.

Thanks,<br>
The Webinar Team
@endcomponent