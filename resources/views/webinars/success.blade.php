@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
            <!-- Success Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-4 sm:px-8 py-4 sm:py-6 text-white">
                <div class="flex flex-col sm:flex-row items-center justify-center text-center sm:text-left">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center mb-3 sm:mb-0 sm:mr-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold">Registration Successful!</h1>
                        <p class="text-green-100 text-sm sm:text-base">Your payment has been confirmed</p>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8">
                <!-- Webinar Details -->
                <div class="mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">{{ $webinar->title }}</h2>
                    <p class="text-gray-600 text-sm sm:text-base">{{ $webinar->description }}</p>
                    
                    <div class="mt-4 flex flex-wrap items-center gap-2 sm:gap-4 text-sm text-gray-500">
                        @if($webinar->scheduled_at)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $webinar->scheduled_at->format('M d, Y - g:i A') }}
                            </div>
                        @endif
                        @if($webinar->duration_minutes)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $webinar->duration_minutes }} minutes
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Access Link Section -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 sm:p-6 mb-6 sm:mb-8">
                    <div class="flex flex-col sm:flex-row items-center mb-4 text-center sm:text-left">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-full flex items-center justify-center mb-2 sm:mb-0 sm:mr-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-blue-900">Your Webinar Access Link</h3>
                            <p class="text-blue-700 text-sm sm:text-base">This link is unique to you and cannot be shared</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-blue-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                            <span class="text-sm text-gray-600 mb-1 sm:mb-0">Secure Access URL:</span>
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded w-fit">
                                Permanent Access
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            <input type="text" 
                                   value="{{ $accessLink }}" 
                                   readonly 
                                   class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs sm:text-sm font-mono break-all"
                                   id="accessLink">
                            <button onclick="copyAccessLink()" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Copy
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-blue-700">
                        <p><strong>Important:</strong></p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>This link is uniquely encrypted for your access only</li>
                            <li>Do not share this link with anyone else</li>
                            <li>This link provides permanent access - save it securely</li>
                            <li>You can use this link anytime to join the webinar</li>
                        </ul>
                    </div>
                </div>

                <!-- Registration Details -->
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6 mb-6 sm:mb-8">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Registration Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <span class="text-xs sm:text-sm text-gray-600">Name:</span>
                            <p class="font-medium text-sm sm:text-base">{{ $registration->full_name }}</p>
                        </div>
                        <div>
                            <span class="text-xs sm:text-sm text-gray-600">Email:</span>
                            <p class="font-medium text-sm sm:text-base">{{ $registration->email }}</p>
                        </div>
                        <div>
                            <span class="text-xs sm:text-sm text-gray-600">Amount Paid:</span>
                            <p class="font-medium text-green-600 text-sm sm:text-base">₵{{ number_format($registration->amount_paid, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-xs sm:text-sm text-gray-600">Payment Status:</span>
                            <p class="font-medium text-green-600 text-sm sm:text-base">Paid</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ $accessLink }}" 
                       target="_blank"
                       class="flex-1 px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all text-sm sm:text-base">
                        Join Webinar Now
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="flex-1 px-4 sm:px-6 py-3 bg-gray-200 text-gray-700 text-center rounded-lg font-semibold hover:bg-gray-300 transition-colors text-sm sm:text-base">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyAccessLink() {
    const input = document.getElementById('accessLink');
    input.select();
    document.execCommand('copy');
    
    // Show success feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = `
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Copied!
    `;
    button.classList.add('bg-green-600', 'hover:bg-green-700');
    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-600', 'hover:bg-green-700');
        button.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }, 2000);
}
</script>
@endsection
