@extends('layouts.app')

@section('title', 'Verify Before Joining - ' . $webinar->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Webinar Notifications -->
        <x-webinar-notifications :webinar="$webinar" />
        <!-- Verification Header -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-8 mb-8 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Final Verification Required</h1>
            <p class="text-gray-600">Please verify your identity before joining the webinar.</p>
        </div>

        <!-- Webinar Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $webinar->title }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Attendee</label>
                    <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                </div>
                @if($webinar->scheduled_at)
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Date & Time</label>
                        <p class="font-medium text-gray-900">{{ $webinar->scheduled_at->format('F j, Y') }}</p>
                        <p class="text-gray-600">{{ $webinar->scheduled_at->format('g:i A') }}</p>
                    </div>
                @endif
            </div>

            <!-- Verification Form -->
            <form method="POST" action="{{ route('webinars.verify.join', [$webinar, $registration]) }}" class="space-y-6">
                @csrf
                
                <!-- User Info Display -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-600">Joining as:</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mt-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="terms_agreed" 
                                required
                                class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-700">
                                I confirm that I am the registered attendee ({{ Auth::user()->name }}) and agree not to share this webinar access with unauthorized individuals. I understand that sharing access may result in immediate removal and account suspension.
                            </span>
                        </label>
                        @error('terms_agreed')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col gap-4">
                    <button 
                        type="submit" 
                        class="w-full py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-semibold text-lg flex items-center justify-center gap-2"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Verify & Join Webinar
                    </button>

                    <div class="text-center">
                        <a href="{{ route('webinars.show', $webinar) }}" class="text-gray-600 hover:text-gray-700 text-sm">
                            ← Back to Webinar Details
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @endsection
