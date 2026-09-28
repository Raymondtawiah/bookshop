@extends('layouts.admin')

@section('title', 'Bookings')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Bookings</h1>
                <p class="page-subtitle">Scheduled sessions from book purchases.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.books') }}" class="p-2.5 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-gray-200" title="Back to books">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <a href="{{ route('admin.bookingSettings') }}" class="p-2.5 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-gray-200" title="Edit booking settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="select-wrapper">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or order..."
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="add-book">Search</button>
                </form>
            </div>
            <div class="weekend-body" style="padding: 0;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Order</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Customer</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Email</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Booking Date</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Booking Time</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Note</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-900 font-medium">#{{ $booking->order_number ?? $booking->id }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $booking->customer_name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $booking->email }}</td>
                                    <td class="px-6 py-4 text-gray-900">
                                        {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('M j, Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">
                                        {{ $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $booking->booking_note ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.bookings.remind', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors" title="Send booking reminder">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($bookings->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
