<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Reminder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-lg mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Your Coaching Session Starts Soon!</h1>
            </div>

            <!-- Meeting Details -->
            <div class="bg-indigo-50 rounded-lg p-4 mb-6">
                <p class="text-gray-600 mb-2">Hi {{ $booking->name }},</p>
                <p class="text-gray-600">
                    This is a friendly reminder that your coaching session is starting in 
                    <span class="font-bold text-indigo-600">{{ $minutesUntil }} minutes</span>.
                </p>
            </div>

            <!-- Join Button -->
            <div class="text-center mb-6">
                <a href="{{ $booking->meeting_link }}" class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90">
                    Join Meeting Now
                </a>
            </div>

            <!-- Meeting Link -->
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Meeting Link:</p>
                <a href="{{ $booking->meeting_link }}" class="text-indigo-600 hover:underline text-sm break-all">{{ $booking->meeting_link }}</a>
            </div>

            <!-- Footer -->
            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-500">See you soon!</p>
            </div>
        </div>
    </div>
</body>
</html>