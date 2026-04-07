@component('mail::message')
# Your Coaching Session Meeting Link

Dear {{ $booking->name }},

Your coaching session meeting link is ready! Please join at the scheduled time using the link below.

## Session Details

**Date:** {{ \Carbon\Carbon::parse($meetingTime)->format('F j, Y') }}
**Time:** {{ \Carbon\Carbon::parse($meetingTime)->format('g:i A') }}

## Meeting Link

Click the button below to join your session:

@component('mail::button', ['url' => $meetingLink])
Join Meeting
@endcomponent

@if($meetingNotes)
## Additional Notes

{{ $meetingNotes }}
@endif

## What to Expect

- Arrive a few minutes early to test your audio/video
- Have your interview materials ready
- Be prepared to discuss your specific concerns

If you have any issues joining the meeting, please contact us immediately.

See you at your session!

Best regards,
The Nathaniel Gyarteng Team
@endcomponent