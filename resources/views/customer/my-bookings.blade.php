<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>My Bookings - {{ config('app.name', 'Nathaniel Gyarteng') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 font-sans">
        <x-flash-message />
        
        @include('components.customer-navbar')

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Coaching Bookings</h1>
                <p class="text-gray-600 mt-2">View your coaching session bookings and their status</p>
            </div>

            @if($bookings->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
                    <p class="text-gray-600 mb-6">You haven't booked any coaching sessions yet.</p>
                    <a href="{{ route('admin.coaching.booking') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                        Book Your First Session
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-2xl shadow-sm p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full 
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full 
                                            @if($booking->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($booking->payment_status === 'failed') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($booking->payment_status) }} Payment
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($booking->package) }} Session</h3>
                                    <p class="text-gray-600">{{ $booking->interview_type }}</p>
                                    <div class="mt-2 flex items-center gap4 text-sm text-gray-500">
                                        <span>{{ $booking->interview_date->format('F j, Y') }} at {{ $booking->interview_time }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-indigo-600">₵{{ number_format($booking->amount ?? 0, 2) }}</p>
                                    <p class="text-sm text-gray-500">Booked on {{ $booking->created_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @include('components.customer-footer')
    </body>
</html>