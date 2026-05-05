@extends('layouts.app')

@section('title', 'Join Webinar - ' . $webinar->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Webinar Notifications -->
        <x-webinar-notifications :webinar="$webinar" />
        <!-- Success Header -->
        <div class="bg-green-50 border border-green-200 rounded-2xl p-8 mb-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Verification Complete!</h1>
            <p class="text-gray-600">Your identity has been verified. You can now join the webinar.</p>
        </div>

        <!-- Webinar Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $webinar->title }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Date & Time</label>
                    @if($webinar->scheduled_at)
                        <p class="font-medium text-gray-900">{{ $webinar->scheduled_at->format('F j, Y') }}</p>
                        <p class="text-gray-600">{{ $webinar->scheduled_at->format('g:i A') }}</p>
                    @else
                        <p class="text-gray-400">To be announced</p>
                    @endif
                </div>
                @if($webinar->duration_minutes)
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Duration</label>
                        <p class="font-medium text-gray-900">{{ $webinar->duration_minutes }} minutes</p>
                    </div>
                @endif
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Your Status</label>
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Verified & Ready
                    </span>
                </div>
            </div>

            <!-- Verified Webinar Link -->
            <div class="border-t border-gray-100 pt-6">
                <label class="block text-sm font-semibold text-gray-900 mb-3">Webinar Meeting Link</label>
                
                <!-- Webinar Link -->
                <div class="bg-gray-50 rounded-xl p-4 break-all">
                    <a href="{{ $webinar->webinar_link }}" target="_blank" rel="noopener noreferrer" 
                       class="text-indigo-600 hover:text-indigo-700 font-medium text-lg">
                        {{ $webinar->webinar_link }}
                    </a>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Your webinar access is ready. Click the link to join.
                </p>
            </div>

            <!-- Join Button -->
            <div class="mt-6">
                <a href="{{ $webinar->webinar_link }}" target="_blank" rel="noopener noreferrer"
                   class="w-full py-4 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-semibold text-lg flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Join Webinar Now
                </a>
            </div>
        </div>

        <!-- Admin Communication Notice -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-amber-900 mb-2">Stay Updated</h3>
                    <p class="text-sm text-amber-800 leading-relaxed">
                        Our admin team will send you important reminders and updates about this webinar, including schedule changes, 
                        meeting link updates, and helpful preparation tips. Please keep an eye on your email and check back here 
                        for any new notifications before the webinar starts.
                    </p>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Important Notes</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Please join the webinar a few minutes before the scheduled time</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Make sure you have a stable internet connection for the best experience</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Your registration and payment have been confirmed</span>
                </li>
            </ul>
        </div>

        <!-- Back to Webinars -->
        <div class="text-center">
            <a href="{{ route('webinars.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                ← Back to All Webinars
            </a>
        </div>
    </div>
@endsection
