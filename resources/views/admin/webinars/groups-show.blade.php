@extends('layouts.admin')

@section('title', $webinar->title . ' - Webinar Group')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">{{ $webinar->title }}</h1>
                <p class="page-subtitle">Registrations and details for this webinar.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.webinars.groups') }}" class="p-2.5 bg-white text-indigo-600 border border-gray-200 rounded-xl hover:bg-indigo-50 transition-colors shadow-sm" title="Back to groups">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <button type="button" onclick="markAllAttended()" class="p-2.5 bg-white text-indigo-600 border border-gray-200 rounded-xl hover:bg-indigo-50 transition-colors shadow-sm" title="Mark all attended">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                <button type="button" onclick="openGroupReminderModal()" class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors shadow-sm" title="Send reminder to all paid">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-700">Overview</h2>
                <button type="button" class="filter-toggle" aria-label="Toggle overview" onclick="toggleGroupOverview()">
                    <span id="group-overview-toggle-icon" style="font-size:18px;font-weight:bold;line-height:1;">&lt;</span>
                </button>
            </div>
            <div id="group-overview-body">
                <div id="group-stats-aggregate" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="text-xs text-gray-500 mb-1">Registrations</div>
                        <div class="text-xl font-bold text-gray-900">{{ $totalRegistrations }}</div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="text-xs text-gray-500 mb-1">Paid</div>
                        <div class="text-xl font-bold text-gray-900">{{ $totalPaid }}</div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="text-xs text-gray-500 mb-1">Pending</div>
                        <div class="text-xl font-bold text-gray-900">{{ $totalPending }}</div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="text-xs text-gray-500 mb-1">Attended</div>
                        <div class="text-xl font-bold text-gray-900">{{ $totalAttended }}</div>
                    </div>
                </div>
                <div id="group-stats-cards" class="hidden grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $webinar->title }}</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $webinar->scheduled_at ? \Carbon\Carbon::parse($webinar->scheduled_at)->format('l, F j, Y \a\t g:i A') : 'No date set' }}
                                </p>
                            </div>
                            @if($webinar->isExpired())
                                <span class="shrink-0 text-[11px] font-medium text-red-700 bg-red-50 border border-red-100 rounded-full px-2 py-1">Expired</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-xs text-gray-600">
                            <div>
                                <span class="block text-gray-500">Registrations</span>
                                <span class="font-semibold text-gray-900">{{ $totalRegistrations }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500">Paid</span>
                                <span class="font-semibold text-gray-900">{{ $totalPaid }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500">Pending</span>
                                <span class="font-semibold text-gray-900">{{ $totalPending }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500">Attended</span>
                                <span class="font-semibold text-gray-900">{{ $totalAttended }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">Phone</th>
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium">Payment</th>
                            <th class="px-4 py-3 font-medium">Attendance</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
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
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No registrations found for this webinar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- GROUP REMINDER MODAL -->
    <div id="group-reminder-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden items-center justify-center z-50 overflow-x-hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full mx-auto max-w-md max-h-[90vh] overflow-y-auto m-4 md:m-8">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Send Group Reminder</h3>
            </div>
            <form id="group-reminder-form" method="POST" action="{{ route('admin.webinars.groups.sendReminder', $webinar) }}">
                @csrf
                <div class="p-4 md:p-6 space-y-4">
                    <p class="text-sm text-gray-600">Send a reminder to all <strong class="text-gray-900">{{ $totalPaid }}</strong> paid attendees for <strong class="text-gray-900">{{ $webinar->title }}</strong>.</p>

                    <div>
                        <label for="group-reminder-type" class="block text-sm font-medium text-gray-700 mb-1">Reminder Type *</label>
                        <select name="reminder_type" id="group-reminder-type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 appearance-none cursor-pointer">
                            <option value="24_hours">24 Hours Before</option>
                            <option value="1_hour">1 Hour Before</option>
                            <option value="15_minutes">15 Minutes Before</option>
                            <option value="post_webinar">Post Webinar</option>
                        </select>
                    </div>

                    <div>
                        <label for="group-reminder-message" class="block text-sm font-medium text-gray-700 mb-1">Custom Message (optional)</label>
                        <textarea name="message" id="group-reminder-message" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 resize-none" placeholder="Any additional message..."></textarea>
                    </div>

                    <div id="group-reminder-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"></div>
                    <div id="group-reminder-success" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm"></div>
                </div>
                <div class="p-4 md:p-6 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeGroupReminderModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Send Reminder</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openGroupReminderModal() {
            const modal = document.getElementById('group-reminder-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeGroupReminderModal() {
            const modal = document.getElementById('group-reminder-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('group-reminder-error').classList.add('hidden');
            document.getElementById('group-reminder-success').classList.add('hidden');
        }

        function markAllAttended() {
            if (!confirm('Mark all registrations in this webinar group as attended?')) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.webinars.groups.markAllAttended', $webinar) }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }

        function toggleGroupOverview() {
            const aggregate = document.getElementById('group-stats-aggregate');
            const cards = document.getElementById('group-stats-cards');
            const icon = document.getElementById('group-overview-toggle-icon');

            if (!aggregate || !cards || !icon) return;

            const isAggregateHidden = aggregate.classList.contains('hidden');

            if (isAggregateHidden) {
                aggregate.classList.remove('hidden');
                cards.classList.add('hidden');
                icon.textContent = '<';
            } else {
                aggregate.classList.add('hidden');
                cards.classList.remove('hidden');
                icon.textContent = '>';
            }
        }

        document.getElementById('group-reminder-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            const errorDiv = document.getElementById('group-reminder-error');
            const successDiv = document.getElementById('group-reminder-success');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');

            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successDiv.textContent = data.message || 'Reminder sent successfully!';
                    successDiv.classList.remove('hidden');
                    setTimeout(() => {
                        closeGroupReminderModal();
                    }, 1500);
                } else {
                    errorDiv.textContent = data.message || 'Failed to send reminder. Please try again.';
                    errorDiv.classList.remove('hidden');
                }
            })
            .catch(err => {
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            });
        });

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('group-reminder-modal');
            if (e.target === modal) {
                closeGroupReminderModal();
            }
        });
    </script>
@endsection
