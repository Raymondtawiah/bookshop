@component('mail::message')
# Webinar Registration Confirmed!

Hello {{ $registration->full_name ?? $registration->user?->name ?? 'there' }},

Your payment for the **{{ $webinar->title }}** has been successfully processed!

## Webinar Details

**When:** Your webinar will be held on Friday

## Your Registration Information

**Registration ID:** {{ str_pad($registration->id, 6, '0', STR_PAD_LEFT) }}  
**Payment Reference:** {{ $registration->transaction_reference ?? 'N/A' }}  
**Amount Paid:** ${{ number_format($registration->amount_paid, 2) }}

## Your Webinar Link

Your webinar link to join the session:

@component('mail::button', ['url' => $webinarLink ?? '#' ])
Join Webinar
@endcomponent

**Important:** Click the button above to join the webinar at the scheduled time.

## What's Next?

1. Save the date and time in your calendar
2. Test your audio and video before the webinar
3. Join 5-10 minutes early to ensure a smooth start

## Important Schedule Information

**This webinar takes place every Friday at 4:00 PM.** You will receive notifications from the admin when it's time for each session. Please keep an eye on your email for upcoming session reminders.

If you have any questions, please don't hesitate to contact us at support@visawithnathaniel.com.

Best regards,<br>
The {{ config('app.name') }} Team
@endcomponent