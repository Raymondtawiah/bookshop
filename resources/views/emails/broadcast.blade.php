<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Important Update from {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
        .update-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4f46e5; }
        .cta-button { display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Exciting Updates from {{ config('app.name') }}</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $name ?? 'Customer' }},</p>
        
        <p>{!! nl2br(e($message ?? '')) !!}</p>
        
        @if($bookUpdate)
        <div class="update-box">
            <h3>🆕 New Books Available</h3>
            <p>{!! nl2br(e($bookUpdate)) !!}</p>
        </div>
        @endif

        @if($webinarUpdate)
        <div class="update-box">
            <h3>🎓 Upcoming Webinars</h3>
            <p>{!! nl2br(e($webinarUpdate)) !!}</p>
        </div>
        @endif
        
        <p>Don't miss these valuable resources. Visit our website now to explore the latest books and register for upcoming webinars!</p>
        
        <a href="{{ $url ?? url('/') }}" class="cta-button">Visit Website Now</a>
        
        <p>If you have any questions, feel free to reach out to us. We're here to help you succeed!</p>
        
        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
