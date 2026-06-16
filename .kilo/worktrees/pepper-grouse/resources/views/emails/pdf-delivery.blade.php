@component('mail::message')
# Your PDF is Ready!

Hello {{ $user->name }},

Thank you for your order! Your PDF document(s) are attached to this email.

## Order Details
- **Order ID:** #{{ $orderId }}

Please find your PDF attachment(s) below.

If you have any questions, please don't hesitate to contact us.

Best regards,<br>
{{ config('app.name', 'Visa Resources') }}
@endcomponent
