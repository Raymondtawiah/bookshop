@component('mail::message')
# Webinar Registration Confirmed!

Hello {{ $registration->full_name ?? $registration->user?->name ?? 'there' }},

You have successfully registered for **{{ $webinar->title }}**. This webinar is currently free!

## Webinar Details

**When:** Your webinar will be held on Friday

## Your Registration Information

**Registration ID:** {{ str_pad($registration->id, 6, '0', STR_PAD_LEFT) }}

## Access Limit

Your secure access link can be used a maximum of **4 times**. After reaching this limit, you will need to re-register.

@if($accessLink)
## Your Webinar Access Link

@component('mail::button', ['url' => $accessLink])
Join Webinar
@endcomponent
@endif

## Important Schedule Information

**This webinar takes place every Friday at 4:00 PM.** You will receive notifications from the admin when it's time for each session. Please keep an eye on your email for upcoming session reminders.

@if(!empty($customMessage))
## Admin Message

{!! nl2br(e($customMessage)) !!}
@endif

Best regards,<br>
The {{ config('app.name') }} Team
@endcomponent
