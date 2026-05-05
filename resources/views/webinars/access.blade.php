@extends('layouts.app')

@section('title', 'Webinar Access')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Access Verification Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">Webinar Access Verified</h1>
                            <p class="text-blue-100">Welcome to {{ $webinar->title }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-blue-100">Access Granted</div>
                        <div class="text-lg font-semibold">{{ now()->format('M d, Y - g:i A') }}</div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <!-- Webinar Content -->
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Main Content Area -->
                    <div class="lg:col-span-2">
                        <!-- Webinar Zoom Link Area -->
                        <div class="bg-black rounded-xl overflow-hidden mb-6" style="aspect-ratio: 16/9;">
                            @if($webinar->webinar_link)
                                <!-- Zoom Webinar -->
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-900 to-indigo-900">
                                    <div class="text-center text-white p-8">
                                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.5 1L2 6v2h19V6m-5.4 6.7L9 15.7l-2.6-2.6L4 15.7 9 20.7l7.4-7.4-2.3-2.6M2 17v2h19v-2H2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold mb-2">Zoom Webinar</h3>
                                        <p class="text-blue-200 mb-6">Click below to join the webinar</p>
                                        <a href="{{ $webinar->webinar_link }}" 
                                           target="_blank"
                                           class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                            Join Zoom Meeting
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- Placeholder when no link is available -->
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800">
                                    <div class="text-center text-white p-8">
                                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold mb-2">Webinar Starting Soon</h3>
                                        <p class="text-gray-300">The webinar link will be available shortly</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Webinar Description -->
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">About This Webinar</h2>
                            <div class="prose prose-gray max-w-none">
                                <p class="text-gray-700">{{ $webinar->description ?? 'Join us for an exclusive webinar session with industry experts.' }}</p>
                            </div>

                            @if($webinar->scheduled_at)
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center text-blue-900">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold">Scheduled Time:</span>
                                    <span class="ml-2">{{ $webinar->scheduled_at->format('l, F j, Y - g:i A') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Your Access Info -->
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Access Information</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Name:</span>
                                    <span class="text-sm font-medium">{{ $registration->full_name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Email:</span>
                                    <span class="text-sm font-medium">{{ $registration->email }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Joined At:</span>
                                    <span class="text-sm font-medium">{{ $registration->joined_at?->format('g:i A') ?? 'Now' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security Notice -->
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-amber-200 rounded-full flex items-center justify-center mr-3 mt-1">
                                    <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-amber-900 mb-1">Security Notice</h4>
                                    <p class="text-xs text-amber-700">This access link is unique to your account. Do not share this URL with others as it may compromise your access.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Support -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                            <p class="text-sm text-gray-600 mb-4">If you're experiencing technical issues or have questions about the webinar, our support team is here to help.</p>
                            <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
