<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #111827;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            background: #4f46e5;
            color: #ffffff;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .header h1 {
            text-align: center;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            text-align: center;
            margin: 0;
            font-size: 16px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .order-details {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #374151;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #4f46e5;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Booking Reminder</h1>
        <p>This is a reminder about your upcoming booking.</p>
    </div>

    <div class="content">
        <p>Hi {{ $order->customer_name }},</p>

        <p>This is a reminder about your scheduled booking session.</p>

        <div class="order-details">
            <h3 style="margin-top: 0;">Booking Details</h3>
            <p><strong>Booking Date:</strong> {{ \Carbon\Carbon::parse($order->booking_date)->format('l, F j, Y') }}</p>
            <p><strong>Booking Time:</strong> {{ \Carbon\Carbon::parse($order->booking_time)->format('g:i A') }} GMT</p>
            @if($order->booking_note)
                <p><strong>Note:</strong> {{ $order->booking_note }}</p>
            @endif
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            @php
                $globalZoomLink = \App\Models\SiteSetting::get('booking_zoom_link');
            @endphp
            @if($globalZoomLink)
                <p><strong>Zoom Link:</strong> <a href="{{ $globalZoomLink }}" target="_blank" style="color: #4f46e5; text-decoration: underline;">Join Session</a></p>
            @endif
        </div>

        <p>If you need to make any changes or have questions, please contact us as soon as possible.</p>

        <p>Best regards,<br>Visa with Nathaniel Team</p>
    </div>
</body>
</html>
