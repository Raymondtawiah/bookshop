@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <div class="mb-6">
        <a href="{{ route('admin.coachings.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Bookings
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 sm:px-6 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Booking Details</h2>
                    <p class="text-sm text-gray-500 mt-1">ID: <span class="font-mono">{{ $booking->id }}</span></p>
                </div>
                <div class="flex gap-2">
                    <span class="px-2 py-1 text-xs font-bold rounded-full
                        @if($booking->payment_status === 'paid') bg-green-100 text-green-700
                        @elseif($booking->payment_status === 'pending') bg-yellow-100 text-yellow-700
                        @else bg-red-100 text-red-700 @endif">
                        Payment: {{ ucfirst($booking->payment_status) }}
                    </span>
                    <span class="px-2 py-1 text-xs font-bold rounded-full
                        @if($booking->status === 'completed') bg-blue-100 text-blue-700
                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
                        @elseif($booking->status === 'confirmed') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="px-4 py-4 sm:px-6 space-y-6">
            <!-- Customer Information Section -->
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Name</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Email</label>
                        <p class="text-sm text-gray-900 mt-1 break-all">{{ $booking->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Phone</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Group Size</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->group_size ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Information Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Booking Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Package</label>
                        <p class="text-sm text-gray-900 mt-1 capitalize">
                            @if($booking->package === 'team')
                                Team Coaching Plan
                            @elseif($booking->package === 'single')
                                1 Week Interview Intensive
                            @else
                                Full Coaching Program
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Interview Type</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->interview_type }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Booking Type</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->booking_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Booking Token</label>
                        <p class="text-sm text-gray-900 mt-1 font-mono">{{ $booking->booking_token ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Interview Schedule Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Interview Schedule</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Interview Date</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->interview_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Interview Time</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->interview_time ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Meeting Details Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Meeting Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Meeting Time</label>
                        <p class="text-sm text-gray-900 mt-1">
                            @if($booking->meeting_time)
                                {{ \Carbon\Carbon::parse($booking->meeting_time)->format('M d, Y g:i A') }}
                            @else
                                Not set
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Meeting Link</label>
                        <p class="text-sm text-gray-900 mt-1">
                            @if($booking->meeting_link)
                                <a href="{{ $booking->meeting_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 break-all">{{ $booking->meeting_link }}</a>
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Meeting Notes</label>
                        <p class="text-sm text-gray-900 mt-1 whitespace-pre-wrap">{{ $booking->meeting_notes ?? 'No meeting notes provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Information Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Amount</label>
                        <p class="text-sm text-gray-900 mt-1 font-semibold">${{ number_format($booking->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Payment Reference</label>
                        <p class="text-sm text-gray-900 mt-1 font-mono">{{ $booking->payment_reference ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Notes</label>
                        <p class="text-sm text-gray-900 mt-1 whitespace-pre-wrap">{{ $booking->notes ?? 'No notes provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Created At</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->created_at->timezone('Africa/Accra')->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 py-3 sm:px-6 border-t border-gray-200 bg-gray-50 flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.coachings.status', $booking->id) }}" class="inline">
                @csrf
                @method('PUT')
                <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded-md hover:bg-indigo-700 text-sm ml-2">Update Status</button>
            </form>

            @if($booking->payment_status === 'paid')
            <form method="POST" action="{{ route('admin.coachings.sendReminder', $booking->id) }}" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm">Send Reminder</button>
            </form>
            @endif

            <form method="POST" action="{{ route('admin.coachings.destroy', $booking->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 text-sm">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection