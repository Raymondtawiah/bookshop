@component('mail::message')
# Webinar Registration Confirmed!

Hello {{ $registration->full_name ?? $registration->user?->name ?? 'there' }},

You have successfully registered for **{{ $webinar->title }}**. This webinar is currently free!

## Webinar Details

@if($webinar->scheduled_at)
**When:** {{ \Carbon\Carbon::parse($webinar->scheduled_at)->format('l, F j, Y \a\t g:i A') }}
@else
**When:** Your webinar will be held on Friday
@endif

## Your Registration Information

**Registration ID:** {{ str_pad($registration->id, 6, '0', STR_PAD_LEFT) }}

## Reminder

We'll send you a reminder email before the webinar starts. That reminder will include the same date and time shown above, along with any last-minute updates.

## Access Limit

Your secure access link can be used a maximum of **1 time**.

@if($accessLink)
## Your Webinar Access Link

@component('mail::button', ['url' => $accessLink])
Join Webinar
@endcomponent
@endif

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
