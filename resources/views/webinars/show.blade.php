@extends('layouts.app')

@section('title', $webinar->title)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Webinar Notifications -->
        <x-webinar-notifications :webinar="$webinar" />
        <!-- Back link -->
        <a href="{{ route('webinars.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 mb-8">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Webinars
        </a>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-8">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-8">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Webinar Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                    <!-- Status Badge -->
                    <div class="mb-6">
                        @if($webinar->status === 'active')
                            <span class="inline-block px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Open for Registration
                            </span>
                        @elseif($webinar->status === 'scheduled')
                            <span class="inline-block px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                Coming Soon
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">
                                {{ ucfirst($webinar->status) }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $webinar->title }}</h1>

                    @if($webinar->description)
                        <div class="prose max-w-none text-gray-600 mb-8">
                            {{ nl2br(e($webinar->description)) }}
                        </div>
                    @endif

                    <!-- Webinar Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-6 border-t border-gray-100">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Date & Time</label>
                            @if($webinar->scheduled_at)
                                <p class="font-medium text-gray-900">
                                    {{ $webinar->scheduled_at->format('F j, Y') }}
                                </p>
                                <p class="text-gray-600">
                                    {{ $webinar->scheduled_at->format('g:i A') }}
                                </p>
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
                            <label class="block text-sm text-gray-500 mb-1">Price</label>
                            <p class="text-2xl font-bold text-indigo-600">
                                @if($webinar->price > 0)
                                    ₵{{ number_format($webinar->price, 2) }}
                                @else
                                    <span class="text-green-600">Free</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Registrations Count -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center gap-8">
                        <div>
                            <p class="text-3xl font-bold text-gray-900">{{ $webinar->total_registrations }}</p>
                            <p class="text-sm text-gray-500">Registered</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-green-600">{{ $webinar->total_paid_registrations }}</p>
                            <p class="text-sm text-gray-500">Paid</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Card -->
            <div>
                @auth
                    @php
                        $registration = $webinar->registrations()->where('user_id', Auth::id())->first();
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                        @if($registration)
                            <!-- Already Registered -->
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Registered!</h3>
                                <p class="text-gray-600">You're signed up for this webinar</p>
                            </div>

                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-gray-600">Registration Status</span>
                                    <span class="font-medium capitalize">{{ $registration->registration_status }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-gray-600">Payment Status</span>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($registration->isPaid())
                                            bg-green-100 text-green-700
                                        @else
                                            bg-yellow-100 text-yellow-700
                                        @endif">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                </div>
                                @if($registration->transaction_reference)
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-600">Transaction ID</span>
                                        <span class="font-mono text-sm text-gray-900">{{ $registration->transaction_reference }}</span>
                                    </div>
                                @endif
                                @if($registration->paid_at)
                                    <div class="flex justify-between py-3">
                                        <span class="text-gray-600">Paid At</span>
                                        <span class="text-gray-900">{{ $registration->paid_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($registration->isPaid())
                                <a href="{{ route('webinars.join', $webinar) }}" class="w-full py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Join Webinar
                                </a>
                            @else
                                <a href="{{ route('webinars.payment', [$webinar, $registration]) }}" class="w-full py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">
                                    Complete Payment
                                </a>
                            @endif
                        @else
                            <!-- Not Registered -->
                            @if($webinar->status === 'active' || $webinar->status === 'scheduled')
                                <form action="{{ route('webinars.register', $webinar) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="full_name" class="block text-sm font-semibold text-gray-900 mb-2">Full Name *</label>
                                        <input 
                                            type="text" 
                                            name="full_name" 
                                            id="full_name"
                                            value="{{ Auth::user()->name }}"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                        >
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address *</label>
                                        <input 
                                            type="email" 
                                            name="email" 
                                            id="email"
                                            value="{{ Auth::user()->email }}"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                        >
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Phone Number (Optional)</label>
                                        <input 
                                            type="tel" 
                                            name="phone" 
                                            id="phone"
                                            value="{{ Auth::user()->phone ?? '' }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                        >
                                    </div>
                                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                                        Register Now
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500 mb-2">Registration not available</p>
                                    <p class="text-sm text-gray-400">This webinar is currently {{ $webinar->status }}</p>
                                </div>
                            @endif
                        @endif

                        @if($webinar->price > 0 && !$registration?->isPaid())
                            <div class="mt-6 p-4 bg-amber-50 rounded-xl">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-amber-800">Payment Required</p>
                                </div>
                                <p class="text-sm text-amber-700">Complete payment to secure your spot and access the webinar link.</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Please Sign In</h3>
                        <p class="text-gray-600 mb-6">You need to be logged in to register for this webinar.</p>
                        <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                            Sign In Now
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Attendee List (for paid users) -->
        @auth
            @php
                $myRegistration = $webinar->registrations()->where('user_id', Auth::id())->first();
            @endphp
            @if($myRegistration && $myRegistration->isPaid() && Auth::user()->is_admin)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Attendee List</h2>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Name</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Phone</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Payment</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($webinar->registrations()->with('user')->get() as $reg)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $reg->full_name }}</div>
                                                @if($reg->user)
                                                    <div class="text-sm text-gray-500">{{ $reg->user->name }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">{{ $reg->email }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $reg->phone ?? '-' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($reg->payment_status === 'paid')
                                                        bg-green-100 text-green-700
                                                    @else
                                                        bg-yellow-100 text-yellow-700
                                                    @endif">
                                                    {{ ucfirst($reg->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($reg->joined_at)
                                                    <span class="text-green-600">Yes</span>
                                                @else
                                                    <span class="text-gray-400">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
@endsection
