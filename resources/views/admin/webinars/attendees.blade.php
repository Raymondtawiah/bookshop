@extends('layouts.admin')

@section('title', 'Webinar Attendees - ' . $webinar->title)

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $webinar->title }}</h1>
                <p class="text-gray-500">Attendee list and management</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.webinars.notifications.create', $webinar->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Notification
                </a>
                <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Webinar
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-sm text-gray-500 mb-1">Total Registrations</div>
                <div class="text-3xl font-bold text-gray-900">{{ $webinar->total_registrations }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-sm text-gray-500 mb-1">Paid Attendees</div>
                <div class="text-3xl font-bold text-green-600">{{ $webinar->total_paid_registrations }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-sm text-gray-500 mb-1">Pending Payment</div>
                <div class="text-3xl font-bold text-yellow-600">{{ $webinar->registrations()->where('payment_status', 'pending')->count() }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-sm text-gray-500 mb-1">Joined Webinar</div>
                <div class="text-3xl font-bold text-blue-600">{{ $webinar->registrations()->whereNotNull('joined_at')->count() }}</div>
            </div>
        </div>

        <!-- Attendees Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($registrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1200px]">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">User Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Phone</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Registration Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Payment Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Payment Reference</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Joined Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Amount Paid</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($registrations as $registration)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">
                                        @if($registration->user)
                                            <div class="font-medium text-gray-900 text-sm break-words">{{ $registration->user->name }}</div>
                                            <div class="text-xs text-gray-500 break-words">{{ $registration->user->email }}</div>
                                        @else
                                            <div class="font-medium text-gray-900 text-sm">User not found</div>
                                            <div class="text-xs text-gray-500">-</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-sm break-words">
                                        @if($registration->user)
                                            {{ $registration->user->email }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-sm break-words">
                                        @if($registration->user && $registration->user->phone)
                                            {{ $registration->user->phone }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap
                                            @if($registration->registration_status === 'registered')
                                                bg-green-100 text-green-700
                                            @elseif($registration->registration_status === 'cancelled')
                                                bg-red-100 text-red-700
                                            @else
                                                bg-gray-100 text-gray-700
                                            @endif">
                                            {{ ucfirst($registration->registration_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap
                                            @if($registration->payment_status === 'paid')
                                                bg-green-100 text-green-700
                                            @elseif($registration->payment_status === 'pending')
                                                bg-yellow-100 text-yellow-700
                                            @else
                                                bg-red-100 text-red-700
                                            @endif">
                                            {{ ucfirst($registration->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 font-mono text-xs break-all">
                                        {{ $registration->transaction_reference ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($registration->joined_at)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full whitespace-nowrap">
                                                Joined
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full whitespace-nowrap">
                                                Not Joined
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 text-sm whitespace-nowrap">
                                        ₵{{ number_format($registration->amount_paid ?? 0, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                                        {{ $registration->paid_at ? $registration->paid_at->format('M d, Y h:i A') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $registrations->appends(request()->query())->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4">No attendees registered yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
