@extends('layouts.app')

@section('title', 'Join Webinar - ' . $webinar->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Success Header -->
        <div class="bg-green-50 border border-green-200 rounded-2xl p-8 mb-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">You're All Set!</h1>
            <p class="text-gray-600">Your payment has been confirmed. You can now join the webinar.</p>
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
                        Paid & Registered
                    </span>
                </div>
            </div>

            <!-- Secure Webinar Link -->
            <div class="border-t border-gray-100 pt-6">
                <label class="block text-sm font-semibold text-gray-900 mb-3">Secure Webinar Access</label>
                
                <!-- Security Warning -->
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">Important Security Notice</p>
                            <p class="text-sm text-red-700 mt-1">
                                This access link is uniquely generated for {{ Auth::user()->name }} and is tied to your account. 
                                Sharing this link will result in immediate access revocation and potential account suspension.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Access Token Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-800">Your Access Token</span>
                    </div>
                    <p class="text-sm text-blue-700">
                        Valid until: {{ $registration->access_token_expires_at ? $registration->access_token_expires_at->format('F j, Y g:i A') : 'N/A' }}
                    </p>
                </div>

                <!-- Protected Webinar Link -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Personal Join Link</label>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-white rounded-lg px-3 py-2 border border-gray-300">
                            <code class="text-xs text-gray-600 break-all">
                                {{ $webinar->webinar_link }}?tk={{ $registration->access_token }}
                            </code>
                        </div>
                        <button onclick="copyLink()" class="p-2 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 transition-colors" title="Copy link">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        This link contains your personal access token and is monitored for unauthorized sharing.
                    </p>
                </div>
            </div>

            <!-- Join Button -->
            <div class="mt-6">
                <a href="{{ $webinar->webinar_link }}?tk={{ $registration->access_token }}" target="_blank" rel="noopener noreferrer"
                   class="w-full py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-semibold text-lg flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Join Webinar with Secure Access
                </a>
            </div>
        </div>

        <!-- Back to Webinars -->
        <div class="text-center">
            <a href="{{ route('webinars.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                ← Back to All Webinars
            </a>
        </div>
    </div>

    <script>
        function copyLink() {
            const linkText = '{{ $webinar->webinar_link }}?tk={{ $registration->access_token }}';
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(linkText).then(() => {
                    // Show success message
                    const button = event.target.closest('button');
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                    button.classList.add('bg-green-100', 'text-green-600');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.classList.remove('bg-green-100', 'text-green-600');
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy link: ', err);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = linkText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Show success message
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                button.classList.add('bg-green-100', 'text-green-600');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-100', 'text-green-600');
                }, 2000);
            }
        }
    </script>
@endsection
