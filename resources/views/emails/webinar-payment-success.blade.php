@component('mail::message')
# Webinar Registration Confirmed!

Hello {{ $registration->full_name ?? $registration->user?->name ?? 'there' }},

Your payment for the **{{ $webinar->title }}** has been successfully processed!

## Webinar Details

**When:** Your webinar will be held on Friday

## Your Registration Information

**Registration ID:** {{ str_pad($registration->id, 6, '0', STR_PAD_LEFT) }}  
**Payment Reference:** {{ $registration->transaction_reference ?? 'N/A' }}
@php
    $provider = $registration->payment_provider ?? 'stripe';
    $isGhs = $provider === 'paystack';
    $symbol = $isGhs ? '₵' : '$';
    $amount = $registration->amount_paid ?? 0;
@endphp
**Amount Paid:** {{ $symbol }}{{ number_format($amount, 2) }}
@if($isGhs)
    <span style="font-size: 12px; color: #666;">(approx. ${{ number_format($amount / 11.65, 2) }} USD)</span>
@endif

## Access Limit

This link can be used a maximum of **4 times**. After reaching this limit, you will need to re-register if payment is still required.

## Your Webinar Link

Your webinar link to join the session:

@component('mail::button', ['url' => $webinarLink ?? '#' ])
Join Webinar
@endcomponent

**Important:** Click the button above to join the webinar at the scheduled time.

## Important Schedule Information

**This webinar takes place every Friday at 4:00 PM.** You will receive notifications from the admin when it's time for each session. Please keep an eye on your email for upcoming session reminders.

If you have any questions, please don't hesitate to contact us at support@visawithnathaniel.com.

Best regards,<br>
The {{ config('app.name') }} Team
@endcomponent