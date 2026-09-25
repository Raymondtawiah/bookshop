@extends('layouts.admin')

@section('title', 'Webinars - Visa with Nathaniel')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Webinars</h1>
                <p class="page-subtitle">Manage webinars, registrations and reminders.</p>
            </div>
            <a href="{{ route('admin.webinars.create') }}" class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors shadow-sm" title="Create webinar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </a>
        </div>

        <!-- STATS -->
        <button type="button" class="filter-toggle" aria-label="Toggle statistics" onclick="toggleFilters('webinar-stats-body')" style="margin-bottom:10px;">
            <span style="font-size:18px;font-weight:bold;line-height:1;">&lt;</span>
        </button>
        <div class="stats-grid" id="webinar-stats-body">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 010 7.75"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Total Registrations</div>
                    <div class="stat-value">{{ $totalRegistrations }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Paid Registrations</div>
                    <div class="stat-value">{{ $totalPaid }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Pending Registrations</div>
                    <div class="stat-value">{{ $totalPending }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-6 mb-6">
            <form method="POST" action="{{ route('admin.webinars.toggleRegistrationForm') }}" class="flex items-center gap-3">
                @csrf
                <span class="text-sm font-medium text-gray-700">Registration form</span>
                <label class="toggle" title="Registration form">
                    <input type="checkbox" name="is_enabled" value="1" class="sr-only" {{ $registrationFormEnabled ? 'checked' : '' }}>
                    <span class="toggle-circle"></span>
                </label>
            </form>

            @if($webinars->isNotEmpty())
                @foreach($webinars as $webinar)
                    <form method="POST" action="{{ route('admin.webinars.togglePayment', $webinar->id) }}" class="flex items-center gap-3">
                        @csrf
                        <span class="text-sm font-medium text-gray-700">{{ $webinar->title }} payment</span>
                        <label class="toggle" title="{{ $webinar->title }} payment">
                            <input type="checkbox" name="payment_enabled" value="1" class="sr-only" {{ $webinar->payment_enabled ? 'checked' : '' }}>
                            <span class="toggle-circle"></span>
                        </label>
                    </form>
                @endforeach
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.toggle input[type="checkbox"]').forEach(function(input) {
                    const toggle = input.closest('.toggle');
                    const circle = toggle ? toggle.querySelector('.toggle-circle') : null;
                    if (!circle) return;

                    function updatePosition() {
                        if (input.checked) {
                            circle.style.transform = 'translateX(-22px)';
                        } else {
                            circle.style.transform = 'translateX(0)';
                        }
                    }

                    updatePosition();
                    input.addEventListener('change', function() {
                        updatePosition();
                        setTimeout(function() {
                            input.closest('form').submit();
                        }, 300);
                    });
                });
            });
        </script>

        <!-- FILTERS -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Filters</h2>
                <button type="button" class="filter-toggle" aria-label="Toggle filters" onclick="toggleFilters('webinar-filter-body')">
                    <span style="font-size:18px;font-weight:bold;line-height:1;">&lt;</span>
                </button>
            </div>
            <div class="panel-body" id="webinar-filter-body">
                <form method="GET" action="{{ route('admin.webinars.index') }}" id="webinar-filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" name="search" placeholder="Name, email, phone..." value="{{ request()->get('search') }}">
                        </div>
                        <div class="form-group">
                            <label>Webinar Timing</label>
                            <div class="select-wrapper">
                                <select name="webinar_timing">
                                    <option value="">All Webinars</option>
                                    <option value="current" {{ request()->get('webinar_timing') == 'current' ? 'selected' : '' }}>Current / Upcoming</option>
                                    <option value="previous" {{ request()->get('webinar_timing') == 'previous' ? 'selected' : '' }}>Previous</option>
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Webinar Type</label>
                            <div class="select-wrapper">
                                <select name="payment_enabled">
                                    <option value="">All Webinars</option>
                                    <option value="1" {{ request()->get('payment_enabled') === '1' ? 'selected' : '' }}>Paid Webinars</option>
                                    <option value="0" {{ request()->get('payment_enabled') === '0' ? 'selected' : '' }}>Free Webinars</option>
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Attendance</label>
                            <div class="select-wrapper">
                                <select name="attendance">
                                    <option value="">All</option>
                                    <option value="attended" {{ request()->get('attendance') == 'attended' ? 'selected' : '' }}>Attended</option>
                                    <option value="not_attended" {{ request()->get('attendance') == 'not_attended' ? 'selected' : '' }}>Not Attended</option>
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label>Date From</label>
                            <input type="date" name="start_date" value="{{ request()->get('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label>Date To</label>
                            <input type="date" name="end_date" value="{{ request()->get('end_date') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- REGISTRATIONS TABLE -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Registrations</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Payment</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Attendance</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($registrations as $registration)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $registration->full_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $registration->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $registration->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $registration->created_at->timezone('Africa/Accra')->format('d M Y, h:i A') }}</td>
                            <td class="px-4 py-3">
                                @if($registration->payment_status === 'paid' && $registration->amount_paid == 0)
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700">Free</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full
                                        @if($registration->payment_status === 'paid') bg-green-100 text-green-700
                                        @elseif($registration->payment_status === 'pending') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full
                                    @if($registration->joined_at) bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $registration->joined_at ? 'Attended' : 'Not Attended' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($registration->payment_status === 'paid')
                                        <form method="POST" action="{{ route('admin.webinars.toggleAttended', [$registration->webinar_id, $registration->id]) }}" class="inline" onsubmit="return confirm('Mark {{ $registration->full_name }} as {{ $registration->joined_at ? 'not attended' : 'attended' }}?')">
                                            @csrf
                                            <button type="submit" class="p-2 {{ $registration->joined_at ? 'text-purple-600 bg-purple-50' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors" title="{{ $registration->joined_at ? 'Mark as not attended' : 'Mark as attended' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if($registration->payment_status === 'paid' && $registration->amount_paid > 0)
                                        <form method="POST" action="{{ route('admin.webinars.sendWebinarReminder', [$registration->webinar_id, $registration->id]) }}" class="inline" onsubmit="return confirm('Send reminder to {{ $registration->full_name }}?')">
                                            @csrf
                                            <input type="hidden" name="reminder_date" value="{{ $registration->webinar && $registration->webinar->scheduled_at ? $registration->webinar->scheduled_at->format('Y-m-d') : date('Y-m-d') }}">
                                            <input type="hidden" name="reminder_time" value="{{ $registration->webinar && $registration->webinar->scheduled_at ? $registration->webinar->scheduled_at->format('H:i') : '09:00' }}">
                                            <button type="submit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Send reminder">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @elseif($registration->payment_status === 'paid' && $registration->amount_paid == 0)
                                        <form method="POST" action="{{ route('admin.webinars.sendFreeReminder', [$registration->webinar_id, $registration->id]) }}" class="inline" onsubmit="return confirm('Send reminder to {{ $registration->full_name }}?')">
                                            @csrf
                                            <input type="hidden" name="reminder_date" value="{{ $registration->webinar && $registration->webinar->scheduled_at ? $registration->webinar->scheduled_at->format('Y-m-d') : date('Y-m-d') }}">
                                            <input type="hidden" name="reminder_time" value="{{ $registration->webinar && $registration->webinar->scheduled_at ? $registration->webinar->scheduled_at->format('H:i') : '09:00' }}">
                                            <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Send reminder">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.webinars.sendPaymentReminder', [$registration->webinar_id, $registration->id]) }}" class="inline" onsubmit="return confirm('Send payment reminder to {{ $registration->full_name }}?')">
                                            @csrf
                                            <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Send payment reminder">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.webinars.registrations.destroy', [$registration->webinar_id, $registration->id]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No registrations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Design concept • Sample data
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('webinar-filter-form');
        if (!form) return;

        const autoSubmitFields = form.querySelectorAll('select, input[type="date"]');
        autoSubmitFields.forEach(function(field) {
            field.addEventListener('change', function() {
                form.submit();
            });
        });

        const searchField = form.querySelector('input[name="search"]');
        if (searchField) {
            let timeout;
            searchField.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    form.submit();
                }, 800);
            });
        }
    });
</script>
@endpush
