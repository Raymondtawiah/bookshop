<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reminderType === 'post_webinar' ? 'Thank You' : 'Webinar Reminder' }} - {{ $webinar->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(to right, #2563eb, #4f46e5); color: white; text-align: center; padding: 40px 30px; }
        .content { padding: 30px; }
        .info-box { padding: 15px; margin-bottom: 20px; border-left: 4px solid; border-radius: 4px; }
        .info-box-blue { background-color: #eff6ff; border-color: #3b82f6; }
        .info-box-amber { background-color: #fffbeb; border-color: #f59e0b; }
        .info-box-red { background-color: #fef2f2; border-color: #ef4444; }
        .info-box-green { background-color: #f0fdf4; border-color: #22c55e; }
        .info-box-indigo { background-color: #eef2ff; border-color: #6366f1; }
        .webinar-details { background-color: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .webinar-details h3 { margin: 0 0 15px 0; color: #111827; }
        .webinar-details .row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .webinar-details .label { color: #6b7280; }
        .webinar-details .value { font-weight: 500; }
        .button { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .button:hover { background-color: #1d4ed8; color: #ffffff; }
        .important-info { background-color: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .important-info h3 { margin: 0 0 15px 0; color: #111827; }
        .important-info ul { margin: 0; padding-left: 20px; font-size: 14px; color: #374151; }
        .important-info li { margin-bottom: 8px; }
        .support { text-align: center; font-size: 14px; color: #6b7280; margin-bottom: 20px; }
        .footer { background-color: #1f2937; color: #d1d5db; text-align: center; padding: 20px 30px; font-size: 14px; }
        .footer p { margin: 5px 0; }
        .footer .small { font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if($reminderType === 'post_webinar')
                <div style="font-size: 32px; margin-bottom: 10px;">🎉</div>
                <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Thank You for Attending!</h1>
                <p style="margin: 10px 0 0 0; color: #dbeafe;">{{ $webinar->title }}</p>
            @else
                <div style="font-size: 32px; margin-bottom: 10px;">📅</div>
                <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Webinar Reminder</h1>
                <p style="margin: 10px 0 0 0; color: #dbeafe;">{{ $webinar->title }}</p>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            @if($customMessage)
                <div class="info-box info-box-indigo">
                    <p style="margin: 0 0 5px 0; color: #312e81; font-weight: 600;">Message from Admin</p>
                    <p style="margin: 0; color: #3730a3;">{{ $customMessage }}</p>
                </div>
            @elseif($reminderType === '24_hours')
                 <div class="info-box info-box-blue">
                     @if($reminderDateTime)
                         <p style="margin: 0 0 5px 0; color: #1e3a8a; font-weight: 600;">Your webinar is scheduled for {{ \Carbon\Carbon::parse($reminderDateTime)->timezone('Africa/Accra')->format('l, F j, Y \a\t g:i A') }}!</p>
                     @elseif($webinar->scheduled_at && $webinar->scheduled_at->isToday())
                         <p style="margin: 0 0 5px 0; color: #1e3a8a; font-weight: 600;">Your webinar is today!</p>
                     @else
                         <p style="margin: 0 0 5px 0; color: #1e3a8a; font-weight: 600;">Your webinar starts tomorrow!</p>
                     @endif
                     <p style="margin: 0; color: #1e40af;">Get ready for an informative session.</p>
                 </div>
             @elseif($reminderType === '1_hour')
                 <div class="info-box info-box-amber">
                     @if($reminderDateTime)
                         <p style="margin: 0 0 5px 0; color: #78350f; font-weight: 600;">Your webinar starts at {{ \Carbon\Carbon::parse($reminderDateTime)->timezone('Africa/Accra')->format('g:i A') }}!</p>
                     @else
                         <p style="margin: 0 0 5px 0; color: #78350f; font-weight: 600;">Starting in 1 hour!</p>
                     @endif
                     <p style="margin: 0; color: #92400e;">Please join a few minutes early.</p>
                 </div>
             @elseif($reminderType === '15_minutes')
                 <div class="info-box info-box-red">
                     @if($reminderDateTime)
                         <p style="margin: 0 0 5px 0; color: #7f1d1d; font-weight: 600;">Your webinar starts at {{ \Carbon\Carbon::parse($reminderDateTime)->timezone('Africa/Accra')->format('g:i A') }}!</p>
                     @else
                         <p style="margin: 0 0 5px 0; color: #7f1d1d; font-weight: 600;">Starting in 15 minutes!</p>
                     @endif
                     <p style="margin: 0; color: #991b1b;">Join now to secure your spot.</p>
                 </div>
             @elseif($reminderType === 'post_webinar')
                 <div class="info-box info-box-green">
                     <p style="margin: 0 0 5px 0; color: #14532d; font-weight: 600;">We hope you enjoyed the webinar!</p>
                     <p style="margin: 0; color: #166534;">Thank you for your participation.</p>
                 </div>
            @endif

            <!-- Webinar Details -->
             <div class="webinar-details">
                 <h3>Webinar Details</h3>
                 <div class="row">
                     <span class="label">Title:</span>
                     <span class="value">{{ $webinar->title }}</span>
                 </div>
                  <div class="row">
                      <span class="label">Webinar Starts:</span>
                      <span class="value">
                          @if($reminderDateTime)
                              {{ \Carbon\Carbon::parse($reminderDateTime)->timezone('Africa/Accra')->format('l, F j, Y \a\t g:i A') }}
                          @elseif($webinar->scheduled_at)
                              {{ $webinar->scheduled_at->timezone('Africa/Accra')->format('l, F j, Y \a\t g:i A') }}
                          @else
                              Check your email for the scheduled date
                          @endif
                      </span>
                  </div>
                 @if($webinar->duration_minutes)
                 <div class="row">
                     <span class="label">Duration:</span>
                     <span class="value">{{ $webinar->duration_minutes }} minutes</span>
                 </div>
                 @endif
             </div>
        </div>

        <!-- Action Section -->
        @if($reminderType !== 'post_webinar')
            <div style="text-align: center; margin-bottom: 20px;">
                @if($accessLink)
                    <a href="{{ $accessLink }}" class="button">
                        {{ $reminderType === '15_minutes' ? 'Join Webinar Now' : 'Access Webinar' }}
                    </a>
                @else
                    <p style="color: #6b7280;">Check your email for the access link before the webinar.</p>
                @endif
            </div>
        @endif

        <!-- Important Information -->
        <div class="important-info">
            <h3>Important Information</h3>
            <ul>
                @if($reminderType !== 'post_webinar')
                    <li>Your access link is unique and secure</li>
                    <li>Join 5-10 minutes early to test your connection</li>
                    <li>Make sure your microphone and camera are working</li>
                @endif
                <li>Check your email for any updates from admin</li>
                @if($reminderType === 'post_webinar')
                    <li>Look out for future webinar announcements</li>
                    <li>Feel free to provide feedback about the session</li>
                @endif
            </ul>
        </div>

        <!-- Support -->
        <div class="support">
            <p>Need help? Contact our support team</p>
            <p class="small" style="margin-top: 5px;">Kindly reach out to us support@visawithnathaniel.com</p>
        </div>
    </div>

    <!-- Footer -->
     <div class="footer">
         <p>&copy; {{ date('Y') }} Visa With Nathaniel. All rights reserved.</p>
         <p class="small" style="margin-top: 5px; word-break: break-all; overflow-wrap: break-word;">This email was sent to {{ $registration->email }} because you registered for "{{ $webinar->title }}".</p>
     </div>
</body>
</html>