@extends('layouts.admin')

@section('title')
    Coaching Management
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Coaching Management</h1>
        <p class="text-gray-600">Manage coaching session bookings and send meeting links to customers</p>
    </div>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 4M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Paid Bookings</p>
                    <p class="text-2xl font-bold text-green-600">{{ $totalPaid }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Pending Payments</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $totalPending }}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Completed Sessions</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalCompleted }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl shadow-lg border border-indigo-100 p-4 sm:p-6 mb-8">
        <form method="GET" action="{{ route('admin.coachings.index') }}" class="space-y-4 sm:space-y-6">
            <!-- Search Bar -->
            <div>
                <label for="search" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Search Bookings</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Search by name, email, or phone..."
                        class="w-full pl-10 pr-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all"
                        value="{{ request()->get('search') }}">
                </div>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label for="package_filter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Package</label>
                    <select name="package" id="package_filter" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All Packages</option>
                        <option value="team" {{ request()->get('package') == 'team' ? 'selected' : '' }}>Team Coaching Plan</option>
                        <option value="single" {{ request()->get('package') == 'single' ? 'selected' : '' }}>1 Week Interview Intensive</option>
                        <option value="premium" {{ request()->get('package') == 'premium' ? 'selected' : '' }}>Full Coaching Program</option>
                    </select>
                </div>
                <div>
                    <label for="payment_status_filter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
                    <select name="payment_status" id="payment_status_filter" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All Payment Statuses</option>
                        <option value="paid" {{ request()->get('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request()->get('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request()->get('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label for="status_filter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Booking Status</label>
                    <select name="status" id="status_filter" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request()->get('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request()->get('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request()->get('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="date_range" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Date Range</label>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <input type="date" name="start_date" id="start_date" class="flex-1 px-3 py-2 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all" value="{{ request()->get('start_date') }}">
                        <input type="date" name="end_date" id="end_date" class="flex-1 px-3 py-2 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all" value="{{ request()->get('end_date') }}">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                <button type="submit" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all font-semibold shadow-md text-sm">
                    Apply Filters
                </button>
                <a href="{{ route('admin.coachings.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 text-gray-600 hover:text-gray-900 hover:bg-white rounded-lg transition-all font-semibold text-center shadow-sm text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-base sm:text-lg font-semibold text-white">Booking List</h2>
            <p class="text-indigo-100 text-xs sm:text-sm">Click on any row to view details or manage booking</p>
        </div>
        @if($bookings->isNotEmpty())
            <table class="w-full table-fixed">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer Name</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Phone</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-28">Package</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Interview Type</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-20">Amount</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Payment</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-24">Meeting</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-2 py-2">
                                <div class="font-semibold text-gray-900 text-xs truncate">{{ $booking->name }}</div>
                            </td>
                            <td class="px-2 py-2 text-xs text-gray-600 truncate">
                                {{ $booking->email }}
                            </td>
                            <td class="px-2 py-2 text-xs text-gray-600 truncate">
                                {{ $booking->phone ?? '-' }}
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                @if($booking->package === 'team')
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">Team</span>
                                @elseif($booking->package === 'single')
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-700">One Week</span>
                                @else
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-purple-100 text-purple-700">Premium</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 text-xs text-gray-600 truncate">
                                {{ $booking->interview_type }}
                            </td>
                            <td class="px-2 py-2 text-xs text-gray-600">
                                {{ $booking->interview_date->format('M d, Y') }}
                            </td>
                            <td class="px-2 py-2 text-xs text-gray-600">
                                ${{ number_format($booking->amount, 2) }}
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                @if($booking->payment_status === 'paid')
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">Paid</span>
                                @elseif($booking->payment_status === 'pending')
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                @else
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700">Failed</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                @if($booking->status === 'completed')
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-700">Completed</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700">Cancelled</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">Confirmed</span>
                                @else
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 text-xs text-gray-600 truncate">
                                @if($booking->meeting_link)
                                    <span class="text-green-600 font-medium">Sent</span>
                                @else
                                    <span class="text-gray-400">None</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="relative inline-block" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open" class="p-1 hover:bg-gray-100 rounded transition-colors" type="button">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 bottom-full mb-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                                         style="display: none;">
                                        <a href="{{ route('admin.coachings.show', $booking->id) }}"
                                           class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-100 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            View Details
                                        </a>
                                        @if($booking->payment_status === 'paid')
                                            <form method="POST" action="{{ route('admin.coachings.sendReminder', $booking->id) }}">
                                                @csrf
                                                <div class="p-2 border-b border-gray-100">
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Send Meeting Reminder</label>
                                                    <p class="text-xs text-gray-500 mb-1">Set the date and time for the coaching session</p>
                                                    <div class="space-y-1">
                                                        <input type="datetime-local" name="reminder_datetime" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500" value="{{ $booking->meeting_time ? \Carbon\Carbon::parse($booking->meeting_time)->format('Y-m-d\TH:i') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="p-2 border-b border-gray-100">
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Meeting Link (optional)</label>
                                                    <p class="text-xs text-gray-500 mb-1">Provide meeting link to include in email</p>
                                                    <div class="space-y-1">
                                                        <input type="url" name="meeting_link" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500" placeholder="https://" value="{{ $booking->meeting_link ?? '' }}">
                                                    </div>
                                                </div>
                                                <button type="submit" class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-100 flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                    </svg>
                                                    Send Reminder
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.coachings.status', $booking->id) }}" class="border-t border-gray-100">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-2">
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Update Status</label>
                                                    <div class="flex gap-1">
                                                        <select name="status" class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                                                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                        <button type="submit" class="px-1.5 py-0.5 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.coachings.sendPaymentReminder', $booking->id) }}">
                                                @csrf
                                                <div class="p-2 border-b border-gray-100">
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Send Payment Reminder</label>
                                                    <p class="text-xs text-gray-500 mb-1">Set deadline date and time (optional)</p>
                                                    <div class="space-y-1">
                                                        <input type="datetime-local" name="deadline_datetime" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500" value="{{ $booking->interview_date ? \Carbon\Carbon::parse($booking->interview_date)->format('Y-m-d\TH:i') : '' }}">
                                                    </div>
                                                </div>
                                                <button type="submit" class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-100 flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Remind to Pay
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.coachings.destroy', $booking->id) }}" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-3 py-1.5 text-left text-xs text-red-600 hover:bg-red-50 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-semibold">No bookings found</p>
                <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or search criteria</p>
            </div>
        @endif
    </div>
</div>
@endsection