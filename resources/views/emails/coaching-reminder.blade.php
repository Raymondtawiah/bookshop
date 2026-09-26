<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Reminder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fafafa] font-sans" style="margin:0; padding:0;">
    <div class="max-w-lg mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: #4f46e5;">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold" style="color: #18181b;">Your Coaching Session Starts Soon!</h1>
            </div>

            <div class="rounded-lg p-4 mb-6" style="background: #eef2ff; border-left: 4px solid #4f46e5;">
                <p class="mb-2" style="color: #52525b;">Hi {{ $booking->name }},</p>
                <p class="mb-2" style="color: #52525b;">
                    This is a friendly reminder that your coaching session is starting in 
                    <span class="font-bold" style="color: #4f46e5;">
                        @php
                            $minutes = (int) $minutesUntil;
                            if ($minutes < 60) {
                                echo $minutes . ' minute' . ($minutes !== 1 ? 's' : '');
                            } elseif ($minutes < 1440) {
                                $hours = floor($minutes / 60);
                                $mins = $minutes % 60;
                                if ($mins === 0) {
                                    echo $hours . ' hour' . ($hours !== 1 ? 's' : '');
                                } else {
                                    echo $hours . ' hour' . ($hours !== 1 ? 's' : '') . ' ' . $mins . ' minute' . ($mins !== 1 ? 's' : '');
                                }
                            } else {
                                $days = floor($minutes / 1440);
                                $hours = floor(($minutes % 1440) / 60);
                                echo $days . ' day' . ($days !== 1 ? 's' : '') . ' ' . $hours . ' hour' . ($hours !== 1 ? 's' : '');
                            }
                        @endphp
                    </span>.
                </p>
                @if($reminderDatetime)
                <p style="color: #52525b;">
                    <span class="font-semibold" style="color: #18181b;">Session Date & Time:</span> 
                    {{ \Carbon\Carbon::parse($reminderDatetime)->format('l, F j, Y \a\t g:i A') }}
                </p>
                @endif
            </div>

            <div class="text-center mb-6">
                <a href="{{ $meetingLink }}" class="inline-block text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90" style="background: #4f46e5; text-decoration: none;">
                    Join Meeting Now
                </a>
            </div>

            <div class="rounded-lg p-4" style="background: #fafafa;">
                <p class="text-sm mb-1" style="color: #6b7280;">Meeting Link:</p>
                <a href="{{ $meetingLink }}" class="text-sm break-all hover:underline" style="color: #374151; text-decoration: none;">{{ $meetingLink }}</a>
            </div>

            <div class="mt-6 pt-6 text-center" style="border-top: 1px solid #e5e7eb;">
                <p class="text-sm" style="color: #6b7280;">See you soon!</p>
            </div>
        </div>
    </div>
</body>
</html>