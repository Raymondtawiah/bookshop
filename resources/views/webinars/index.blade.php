@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Upcoming Webinars</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Join our expert-led webinars and learn from industry professionals. 
                Register early and secure your spot!
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-8">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-6 py-4 rounded-xl mb-8">
                {{ session('info') }}
            </div>
        @endif

        @if($webinars->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Webinars Scheduled</h3>
                <p class="text-gray-500 mb-6">Check back soon for upcoming webinars!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($webinars as $webinar)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <!-- Webinar Status Badge -->
                        <div class="p-4 border-b border-gray-100">
                            @if($webinar->status === 'active')
                                <span class="text-xs font-semibold px-3 py-1 bg-green-100 text-green-700 rounded-full">
                                    Open for Registration
                                </span>
                            @elseif($webinar->status === 'scheduled')
                                <span class="text-xs font-semibold px-3 py-1 bg-blue-100 text-blue-700 rounded-full">
                                    Coming Soon
                                </span>
                            @else
                                <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-700 rounded-full">
                                    {{ ucfirst($webinar->status) }}
                                </span>
                            @endif
                        </div>

                        <!-- Webinar Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                {{ $webinar->title }}
                            </h3>

                            @if($webinar->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $webinar->description }}
                                </p>
                            @endif

                            <!-- Webinar Details -->
                            <div class="space-y-3 mb-6">
                                @if($webinar->scheduled_at)
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $webinar->scheduled_at->format('F j, Y \a\t g:i A') }}
                                    </div>
                                @endif

                                @if($webinar->duration_minutes)
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $webinar->duration_minutes }} minutes
                                    </div>
                                @endif

                                <div class="flex items-center text-gray-600 text-sm">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $webinar->registrations()->count() }} registered
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <span class="text-sm text-gray-500">Price</span>
                                    <p class="text-2xl font-bold text-indigo-600">
                                        @if($webinar->price > 0)
                                            ₵{{ number_format($webinar->price, 2) }}
                                        @else
                                            <span class="text-green-600">Free</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Action Button -->
                                @auth
                                    @if($webinar->status === 'active' || $webinar->status === 'scheduled')
                                        @if($webinar->registrations()->where('user_id', Auth::id())->exists())
                                            <a href="{{ route('webinars.show', $webinar) }}" 
                                               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-sm">
                                                View Details
                                            </a>
                                        @else
                                            <a href="{{ route('webinars.show', $webinar) }}" 
                                               class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium text-sm">
                                                Register Now
                                            </a>
                                        @endif
                                    @else
                                        <span class="px-6 py-3 bg-gray-100 text-gray-400 rounded-xl font-medium text-sm cursor-not-allowed">
                                            Not Available
                                        </span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium text-sm">
                                        Login to Register
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 text-center">
                {{ $webinars->links() }}
            </div>
        @endif
    </div>
@endsection

