@extends('layouts.admin')

@section('title', 'Coaching - Visa with Nathaniel')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Coaching</h1>
                <p class="page-subtitle">Manage coaching bookings and sessions.</p>
            </div>
            <a href="{{ route('coaching.booking') }}" class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors shadow-sm" title="New booking">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </a>
        </div>

        <!-- STATS -->
        <button type="button" class="filter-toggle" aria-label="Toggle statistics" onclick="toggleFilters('coaching-stats-body')" style="margin-bottom:10px;">
            <span style="font-size:18px;font-weight:bold;line-height:1;">&lt;</span>
        </button>
        <div class="stats-grid" id="coaching-stats-body">
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
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-value">{{ $totalBookings }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Paid Bookings</div>
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
                    <div class="stat-label">Pending Payments</div>
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

        <!-- FILTERS -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Filters</h2>
                <button type="button" class="filter-toggle" aria-label="Toggle filters" onclick="toggleFilters('coaching-filter-body')">
                    <span style="font-size:18px;font-weight:bold;line-height:1;">&lt;</span>
                </button>
            </div>
            <div class="panel-body" id="coaching-filter-body">
                <form method="GET" action="{{ route('admin.coachings.index') }}" id="coaching-filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" name="search" placeholder="Name, email, phone..." value="{{ request()->get('search') }}">
                        </div>
                        <div class="form-group">
                            <label>Package</label>
                            <div class="select-wrapper">
                                <select name="package">
                                    <option value="">All Packages</option>
                                    <option value="team" {{ request()->get('package') == 'team' ? 'selected' : '' }}>Team Coaching Plan</option>
                                    <option value="single" {{ request()->get('package') == 'single' ? 'selected' : '' }}>1 Week Interview Intensive</option>
                                    <option value="premium" {{ request()->get('package') == 'premium' ? 'selected' : '' }}>Full Coaching Program</option>
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Payment Status</label>
                            <div class="select-wrapper">
                                <select name="payment_status">
                                    <option value="">All Statuses</option>
                                    <option value="paid" {{ request()->get('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="pending" {{ request()->get('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ request()->get('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Booking Status</label>
                            <div class="select-wrapper">
                                <select name="status">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request()->get('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ request()->get('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request()->get('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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

        <!-- BOOKINGS TABLE -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Bookings</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Package</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Interview Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Time</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Payment</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $booking->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $booking->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $booking->phone ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($booking->package === 'team')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Team</span>
                                @elseif($booking->package === 'single')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">One Week</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700">Premium</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $booking->interview_type }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $booking->interview_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $booking->interview_time ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($booking->payment_status === 'paid')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Paid</span>
                                @elseif($booking->payment_status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Failed</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($booking->status === 'completed')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">Completed</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Cancelled</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Confirmed</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" class="p-2 text-gray-500 hover:bg-gray-50 rounded-lg transition-colors" title="View details" onclick="openBookingModal({{ $booking->id }}, '{{ addslashes($booking->name) }}', '{{ addslashes($booking->email) }}', '{{ $booking->phone ?? '' }}', '{{ addslashes($booking->package) }}', '{{ addslashes($booking->interview_type) }}', '{{ $booking->interview_date->format('Y-m-d') }}', '{{ $booking->interview_time ?? '' }}', '{{ ucfirst($booking->payment_status) }}', '{{ ucfirst($booking->status) }}', '{{ $booking->amount ?? '0' }}', '{{ addslashes($booking->payment_reference ?? '') }}')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    @if($booking->payment_status === 'paid')
                                        <form method="POST" action="{{ route('admin.coachings.sendReminder', $booking->id) }}" class="inline" onsubmit="return confirm('Send reminder to {{ $booking->name }}?')">
                                            @csrf
                                            <button type="submit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Send reminder">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.coachings.sendPaymentReminder', $booking->id) }}" class="inline" onsubmit="return confirm('Send payment reminder to {{ $booking->name }}?')">
                                            @csrf
                                            <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Send payment reminder">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.coachings.destroy', $booking->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking?')">
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
                            <td colspan="10" class="px-4 py-8 text-center text-gray-500">No bookings found</td>
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
    <!-- BOOKING DETAILS MODAL -->
    <div id="booking-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden items-center justify-center z-50 overflow-y-auto overflow-x-hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full mx-auto max-w-2xl max-h-[90vh] overflow-y-auto m-4 md:m-8">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Booking Details</h3>
            </div>
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p id="modal-name" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p id="modal-email" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone</label>
                        <p id="modal-phone" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Package</label>
                        <p id="modal-package" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Interview Type</label>
                        <p id="modal-interview-type" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date</label>
                        <p id="modal-date" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Time</label>
                        <p id="modal-time" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Payment Status</label>
                        <p id="modal-payment-status" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Booking Status</label>
                        <p id="modal-status" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Amount</label>
                        <p id="modal-amount" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Payment Reference</label>
                        <p id="modal-reference" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                </div>
                                @if($booking->notes)
                                <div class="mt-3 md:mt-4">
                                    <label class="block text-sm font-medium text-gray-500">Notes</label>
                                    <p id="modal-notes-{{ $booking->id }}" class="text-sm font-semibold text-gray-900 break-words">{{ $booking->notes }}</p>
                                </div>
                                @endif
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end">
                <button type="button" onclick="closeBookingModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openBookingModal(id, name, email, phone, package, interviewType, date, time, paymentStatus, status, amount, reference) {
            document.getElementById('modal-name').textContent = name;
            document.getElementById('modal-email').textContent = email;
            document.getElementById('modal-phone').textContent = phone || '-';
            document.getElementById('modal-package').textContent = package;
            document.getElementById('modal-interview-type').textContent = interviewType;
            document.getElementById('modal-date').textContent = date;
            document.getElementById('modal-time').textContent = time || '-';
            document.getElementById('modal-payment-status').textContent = paymentStatus;
            document.getElementById('modal-status').textContent = status;
            document.getElementById('modal-amount').textContent = '$' + (amount || '0.00');
            document.getElementById('modal-reference').textContent = reference || '-';

            const modal = document.getElementById('booking-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeBookingModal() {
            const modal = document.getElementById('booking-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('coaching-filter-form');
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
