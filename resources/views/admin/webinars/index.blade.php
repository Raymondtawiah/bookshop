@extends('layouts.admin')

@section('title', 'Webinar Management')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Webinar Management</h1>
        <p class="text-gray-600">Manage webinar registrations and send reminders to attendees</p>
    </div>

    <!-- Registration Form Toggle -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="POST" action="{{ route('admin.webinars.toggleRegistrationForm') }}" class="flex flex-wrap items-center gap-4">
            @csrf
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_enabled" value="1" class="sr-only peer" {{ $registrationFormEnabled ? 'checked' : '' }} onchange="this.form.submit()">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                <span class="ml-3 text-sm font-medium text-gray-900">Registration Form {{ $registrationFormEnabled ? 'Enabled' : 'Disabled' }}</span>
            </label>
        </form>
    </div>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Registrations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalRegistrations }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Paid Registrations</p>
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
                    <p class="text-sm font-medium text-gray-500 mb-1">Pending Registrations</p>
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Attended Participants</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalAttended }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @php
        $webinarChartData = [
            'labels' => ['Paid', 'Pending', 'Attended', 'Others'],
            'values' => [
                (int) $totalPaid,
                (int) $totalPending,
                (int) $totalAttended,
                max(0, (int) $totalRegistrations - (int) $totalPaid - (int) $totalPending - (int) $totalAttended),
            ],
        ];
    @endphp

    <!-- Webinar Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col items-center">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Webinar Overview</h2>
        <div class="w-full max-w-md">
            <canvas id="webinarChart"></canvas>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl shadow-lg border border-indigo-100 p-4 sm:p-6 mb-8">
        <form method="GET" action="{{ route('admin.webinars.index') }}" class="space-y-4 sm:space-y-6">
            <!-- Search Bar -->
            <div>
                <label for="search" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Search Registrations</label>
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
                    <label for="payment_status_filter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
                    <select name="payment_status" id="payment_status_filter" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All Statuses</option>
                        <option value="paid" {{ request()->get('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request()->get('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request()->get('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label for="attendance_filter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Attendance</label>
                    <select name="attendance" id="attendance_filter" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All</option>
                        <option value="attended" {{ request()->get('attendance') == 'attended' ? 'selected' : '' }}>Attended</option>
                        <option value="not_attended" {{ request()->get('attendance') == 'not_attended' ? 'selected' : '' }}>Not Attended</option>
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
                <a href="{{ route('admin.webinars.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 text-gray-600 hover:text-gray-900 hover:bg-white rounded-lg transition-all font-semibold text-center shadow-sm text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-base sm:text-lg font-semibold text-white">Registration List</h2>
            <p class="text-indigo-100 text-xs sm:text-sm">Click on any row to toggle attendance status</p>
        </div>
        @if($registrations->isNotEmpty())
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Registrant Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Registration Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Attendance</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Reminders</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($registrations as $registration)
                            <tr class="hover:bg-indigo-50 transition-colors">
                                <td class="px-3 py-3">
                                    <div class="font-semibold text-gray-900 text-xs">{{ $registration->full_name }}</div>
                                </td>
                                <td class="px-3 py-3 text-xs text-gray-600">
                                    {{ $registration->email }}
                                </td>
                                <td class="px-3 py-3 text-xs text-gray-600">
                                    {{ $registration->phone ?? '-' }}
                                </td>
                                <td class="px-3 py-3 text-xs text-gray-600">
                                    {{ $registration->created_at->timezone('Africa/Accra')->format('d M Y, h:i A') }}
                                </td>
                                <td class="px-3 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full
                                        @if($registration->payment_status === 'paid') bg-green-100 text-green-700
                                        @elseif($registration->payment_status === 'pending') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full
                                        @if($registration->joined_at) bg-purple-100 text-purple-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $registration->joined_at ? 'Attended' : 'Not Attended' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    @if($registration->last_reminder_sent)
                                        <div class="text-xs">
                                            <span class="font-semibold text-gray-900">{{ $registration->reminder_count ?? 0 }}</span>
                                            <span class="text-gray-500">sent</span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $registration->last_reminder_sent->timezone('Africa/Accra')->format('M j, g:i A') }}
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">None</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                     <div class="relative inline-block" x-data="{ open: false }" @click.outside="open = false">
                                         <button @click="open = !open" class="p-2 hover:bg-gray-100 rounded-lg transition-colors" type="button">
                                             <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                              class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                                              style="display: none;">
                                             @if($registration->payment_status === 'paid')
                                                 <form method="POST" action="{{ route('admin.webinars.sendWebinarReminder', [$registration->webinar_id, $registration->id]) }}">
                                                     @csrf
                                                     <div class="p-3 border-b border-gray-100">
                                                          <label class="block text-xs font-semibold text-gray-700 mb-1">Webinar Date & Time</label>
                                                          <div class="flex gap-2 mb-2">
                                                              <input type="date" name="reminder_date" class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500" value="{{ $registration->webinar && $registration->webinar->scheduled_at ? $registration->webinar->scheduled_at->format('Y-m-d') : date('Y-m-d') }}">
                                                              <input type="time" name="reminder_time" class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500" value="{{ $registration->webinar && $registration->webinar->scheduled_at ? $registration->webinar->scheduled_at->format('H:i') : '09:00' }}">
                                                          </div>
                                                          <p class="text-xs text-gray-500">This will be included in the reminder email</p>
                                                      </div>
                                                     <div class="p-3 border-b border-gray-100">
                                                         <label class="block text-xs font-semibold text-gray-700 mb-1">Custom Message (Optional)</label>
                                                         <textarea name="custom_message" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500" rows="2" placeholder="Add a custom message to include in the reminder email..."></textarea>
                                                     </div>
                                                     <button type="submit" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                         <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                         </svg>
                                                         Send Webinar Reminder
                                                     </button>
                                                 </form>
                                                 <form method="POST" action="{{ route('admin.webinars.toggleAttended', [$registration->webinar_id, $registration->id]) }}">
                                                     @csrf
                                                     <button type="submit" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                         <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                         </svg>
                                                         {{ $registration->joined_at ? 'Mark Not Attended' : 'Mark Attended' }}
                                                     </button>
                                                 </form>
                                             @else
                                                 <form method="POST" action="{{ route('admin.webinars.sendPaymentReminder', [$registration->webinar_id, $registration->id]) }}">
                                                     @csrf
                                                     <div class="p-3 border-b border-gray-100">
                                                         <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Due Date (Optional)</label>
                                                         <input type="date" name="reminder_date" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500" value="{{ date('Y-m-d') }}">
                                                         <p class="text-xs text-gray-500 mt-1">Will be included in payment reminder</p>
                                                     </div>
                                                     <button type="submit" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                         <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                         </svg>
                                                         Remind to Pay
                                                     </button>
                                                 </form>
                                             @endif
                                             <form method="POST" action="{{ route('admin.webinars.registrations.destroy', [$registration->webinar_id, $registration->id]) }}" onsubmit="return confirm('Are you sure you want to delete this registration?');">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="submit" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
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
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a6 6 0 100-12 6 6 0 000 12z" />
                    </svg>
                </div>
                <p class="text-gray-500 font-semibold">No registrations found</p>
                <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or search criteria</p>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('webinarChart');
        if (!ctx) return;

        const chart = @json($webinarChartData);

        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: chart.labels,
                datasets: [{
                    label: 'Registrations',
                    data: chart.values,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(156, 163, 175, 0.8)',
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>

@endsection
