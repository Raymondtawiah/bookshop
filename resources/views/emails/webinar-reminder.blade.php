<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reminderType === 'post_webinar' ? 'Thank You' : 'Webinar Reminder' }} - {{ $webinar->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white text-center">
            @if($reminderType === 'post_webinar')
                <div class="text-4xl mb-2">🎉</div>
                <h1 class="text-2xl font-bold">Thank You for Attending!</h1>
                <p class="text-blue-100 mt-2">{{ $webinar->title }}</p>
            @else
                <div class="text-4xl mb-2">📅</div>
                <h1 class="text-2xl font-bold">Webinar Reminder</h1>
                <p class="text-blue-100 mt-2">{{ $webinar->title }}</p>
            @endif
        </div>

        <!-- Content -->
        <div class="px-6 py-8">
            @if($reminderType === '24_hours')
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-900 font-semibold">Your webinar starts tomorrow!</p>
                    <p class="text-blue-700">Get ready for an informative session.</p>
                </div>
            @elseif($reminderType === '1_hour')
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6">
                    <p class="text-amber-900 font-semibold">Starting in 1 hour!</p>
                    <p class="text-amber-700">Please join a few minutes early.</p>
                </div>
            @elseif($reminderType === '15_minutes')
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-red-900 font-semibold">Starting in 15 minutes!</p>
                    <p class="text-red-700">Join now to secure your spot.</p>
                </div>
            @elseif($reminderType === 'post_webinar')
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <p class="text-green-900 font-semibold">We hope you enjoyed the webinar!</p>
                    <p class="text-green-700">Thank you for your participation.</p>
                </div>
            @endif

            <!-- Webinar Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-900 mb-3">Webinar Details</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Title:</span>
                        <span class="font-medium">{{ $webinar->title }}</span>
                    </div>
                    @if($webinar->scheduled_at && $reminderType !== 'post_webinar')
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date & Time:</span>
                            <span class="font-medium">{{ $webinar->scheduled_at->format('l, F j, Y - g:i A') }}</span>
                        </div>
                    @endif
                    @if($webinar->duration_minutes)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-medium">{{ $webinar->duration_minutes }} minutes</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Your Email:</span>
                        <span class="font-medium">{{ $registration->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Section -->
            @if($reminderType !== 'post_webinar')
                <div class="text-center mb-6">
                    <a href="{{ route('webinars.access', [$webinar->id, $registration->access_token]) }}" 
                       class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        {{ $reminderType === '15_minutes' ? 'Join Webinar Now' : 'Access Webinar' }}
                    </a>
                </div>
            @endif

            <!-- Important Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-900 mb-3">Important Information</h3>
                <ul class="text-sm text-gray-700 space-y-2">
                    @if($reminderType !== 'post_webinar')
                        <li>• Your access link is unique and secure</li>
                        <li>• Join 5-10 minutes early to test your connection</li>
                        <li>• Make sure your microphone and camera are working</li>
                    @endif
                    <li>• Check your email for any updates from admin</li>
                    @if($reminderType === 'post_webinar')
                        <li>• Look out for future webinar announcements</li>
                        <li>• Feel free to provide feedback about the session</li>
                    @endif
                </ul>
            </div>

            <!-- Support -->
            <div class="text-center text-sm text-gray-600">
                <p>Need help? Contact our support team</p>
                <p class="text-xs text-gray-500 mt-1">This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-800 px-6 py-4 text-center text-gray-300 text-sm">
            <p>&copy; {{ date('Y') }} Bookshop. All rights reserved.</p>
            <p class="text-xs mt-1">This email was sent to {{ $registration->email }} because you registered for "{{ $webinar->title }}".</p>
        </div>
    </div>
</body>
</html>
