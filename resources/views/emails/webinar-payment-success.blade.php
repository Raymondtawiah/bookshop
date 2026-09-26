@component('mail::message')
# Webinar Registration Confirmed!

Hello {{ $registration->full_name ?? $registration->user?->name ?? 'there' }},

Your payment for the **{{ $webinar->title }}** has been successfully processed!

## Webinar Details

@if($webinar->scheduled_at)
**When:** {{ \Carbon\Carbon::parse($webinar->scheduled_at)->format('l, F j, Y \a\t g:i A') }}
@else
**When:** Your webinar will be held on Friday
@endif

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
    <span style="font-size: 12px; color: #6b7280;">(approx. ${{ number_format($amount / 11.65, 2) }} USD)</span>
@endif

## Reminder

We'll send you a reminder email before the webinar starts. That reminder will include the same date and time shown above, along with any last-minute updates.

## Access Limit

This link can be used a maximum of **1 time**.

## Your Webinar Link

Your webinar link to join the session:

@component('mail::button', ['url' => $webinarLink ?? '#' ])
Join Webinar
@endcomponent

**Important:** Click the button above to join the webinar at the scheduled time.

@if($webinar->scheduled_at)
## Important Schedule Information

**Date & Time:** {{ \Carbon\Carbon::parse($webinar->scheduled_at)->format('l, F j, Y \a\t g:i A') }}
@if($webinar->duration_minutes)
**Duration:** {{ $webinar->duration_minutes }} minutes
@endif
@endif

@if(!empty($customMessage))
## Further Information

{!! nl2br(e($customMessage)) !!}
@endif

Best regards,<br>
{{ config('app.name') }} 
@endcomponent