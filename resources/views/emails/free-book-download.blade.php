@component('mail::message')
# Your Free Book is Ready!

Dear {{ $lead->full_name }},

Thank you for your interest! Your free book **{{ $lead->book_title }}** is ready for download.

@component('mail::button', ['url' => route('free-book.download', $lead->download_token)])
Download Now
@endcomponent

**Note:** This download link is unique to you. Please keep it secure.

If you have any questions, feel free to reply to this email.

Best regards,  
{{ config('app.name', 'Bookshop') }}
@endcomponent
