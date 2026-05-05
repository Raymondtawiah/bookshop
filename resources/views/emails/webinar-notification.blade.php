<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }} - {{ $webinar->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 16px;
        }
        .notification-box {
            margin: 25px 0;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid;
        }
        .urgent {
            background-color: #fef2f2;
            border-left-color: #ef4444;
        }
        .schedule {
            background-color: #eff6ff;
            border-left-color: #3b82f6;
        }
        .zoom_update {
            background-color: #faf5ff;
            border-left-color: #a855f7;
        }
        .info {
            background-color: #f9fafb;
            border-left-color: #6b7280;
        }
        .notification-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1f2937;
        }
        .notification-message {
            white-space: pre-line;
            color: #374151;
            line-height: 1.6;
        }
        .webinar-info {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .webinar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .webinar-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }
        .cta-button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .cta-button:hover {
            background-color: #4338ca;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .expiration {
            font-size: 12px;
            color: #6b7280;
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">📚 Bookshop</div>
            <div class="title">Webinar Notification</div>
            <div class="subtitle">Hello {{ $user->name }}, important update about your registered webinar</div>
        </div>

        <!-- Notification Content -->
        <div class="notification-box {{ $notification->type }}">
            <div class="notification-title">{{ $notification->title }}</div>
            <div class="notification-message">{{ $notification->message }}</div>
            @if($notification->expires_at)
                <div class="expiration">
                    This notification expires on: {{ $notification->expires_at->format('F j, Y \a\t g:i A') }}
                </div>
            @endif
        </div>

        <!-- Webinar Information -->
        <div class="webinar-info">
            <div class="webinar-title">{{ $webinar->title }}</div>
            <div class="webinar-details">
                <div class="detail-item">
                    <div class="detail-label">Date & Time</div>
                    <div class="detail-value">
                        @if($webinar->scheduled_at)
                            {{ $webinar->scheduled_at->format('F j, Y \a\t g:i A') }}
                        @else
                            To be announced
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Duration</div>
                    <div class="detail-value">
                        @if($webinar->duration_minutes)
                            {{ $webinar->duration_minutes }} minutes
                        @else
                            Not specified
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Price</div>
                    <div class="detail-value">₵{{ number_format($webinar->price, 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">{{ ucfirst($webinar->status) }}</div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ route('webinars.show', $webinar->id) }}" class="cta-button">
                View Webinar Details
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This notification was sent to {{ $user->email }} because you are registered for "{{ $webinar->title }}".</p>
            <p>Your registration status: 
                @if($webinar->registrations()->where('user_id', $user->id)->where('payment_status', 'paid')->exists())
                    <span style="color: #059669; font-weight: 600;">✅ Paid & Confirmed</span>
                @else
                    <span style="color: #d97706; font-weight: 600;">⏳ Payment Pending</span>
                @endif
            </p>
            <p>If you have any questions, please contact our support team.</p>
            <p>© {{ date('Y') }} Bookshop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
