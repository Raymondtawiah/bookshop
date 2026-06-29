@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Staff Management</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Manage all staff accounts and attendance.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mb-0.5">Total Staff</p>
            <p class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $totalStaff }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/30 dark:to-orange-800/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mb-0.5">Pending Approval</p>
            <p class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $pendingStaff }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mb-0.5">Approved Staff</p>
            <p class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $approvedStaff }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mb-0.5">Rejected Staff</p>
            <p class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $rejectedStaff }}</p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Monthly Attendance Trend</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Last 7 months overview</p>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6 flex flex-col items-center">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100 mb-6 self-start">Staff Status</h3>
            <div class="relative w-40 h-40 mb-6">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="w-full space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span><span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Present</span></div>
                    <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $approvedStaff }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500"></span><span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Pending</span></div>
                    <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $pendingStaff }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span><span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Rejected</span></div>
                    <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $rejectedStaff }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Records Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-700">
            <h2 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Attendance Records</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Review and manage staff attendance submissions</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50 text-zinc-600 dark:text-zinc-400 font-semibold">
                    <tr>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Staff</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Role</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Date</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Approved By</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @if($attendances->count() > 0)
                        @foreach($attendances as $record)
                        <tr class="card-hover">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md">{{ $record->user->initials() }}</div>
                                    <div>
                                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $record->user->name }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $record->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-100 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">
                                    {{ ucfirst(str_replace('_', ' ', $record->user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ \Carbon\Carbon::parse($record->attendance_date)->format('M j, Y') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $status = $record->status;
                                    $statusColors = ['present' => 'emerald', 'pending' => 'amber', 'rejected' => 'red'];
                                    $statusColor = $statusColors[$status] ?? 'zinc';
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-400 ring-1 ring-{{ $statusColor }}-200 dark:ring-{{ $statusColor }}-700">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ optional($record->approver)->name ?? 'Admin' }}</td>
                            <td class="px-6 py-4">
                                @if($record->status === 'present')
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600" title="Approved">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @elseif($record->status === 'pending')
                                    <form action="{{ route('admin.staff.approve', $record->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Approve">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <button type="button" onclick="openRejectModal({{ $record->id }}, '{{ $record->user->name }}')" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Reject">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600" title="Rejected">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-500">No attendance records found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- All Staff Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">All Staff</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Complete staff directory</p>
            </div>
            <a href="{{ route('admin.staff.create') }}" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md flex items-center justify-center gap-1 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Staff
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50 text-zinc-600 dark:text-zinc-400 font-semibold">
                    <tr>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Profile</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Full Name</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Email</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Role</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap hidden md:table-cell">Phone</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap hidden lg:table-cell">Created</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @if($staff->count() > 0)
                        @foreach($staff as $member)
                        <tr class="card-hover">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md">{{ $member->initials() }}</div>
                                    <div><p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $member->name }}</p><p class="text-xs text-zinc-500 dark:text-zinc-400">Staff</p></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ $member->name }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ $member->email }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-100 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">
                                    {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 hidden md:table-cell">{{ $member->phone_number ?? '—' }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 hidden lg:table-cell">{{ $member->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end">
                                    <a href="{{ route('admin.staff.attendance', $member->id) }}" class="p-2 text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="View Attendance History">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002-2v12a2 2 0 002 2z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500">No staff members found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100 mb-4">Reject Attendance</h3>
            <p id="rejectModalName" class="text-sm text-zinc-600 dark:text-zinc-400 mb-4"></p>
            <input type="hidden" id="rejectAttendanceId" value="">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea id="rejectReason" rows="3" class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none" placeholder="Enter reason for rejection..."></textarea>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="submitReject()" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-lg transition-colors">Reject</button>
                <button onclick="closeRejectModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-600 text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">Cancel</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Attendance Trend Chart
        const monthlyData = @json($monthlyTrend);
        if (document.getElementById('attendanceChart') && monthlyData.length > 0) {
            const ctx1 = document.getElementById('attendanceChart').getContext('2d');
            const grad1 = ctx1.createLinearGradient(0, 0, 0, 250);
            grad1.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
            grad1.addColorStop(1, 'rgba(99, 102, 241, 0)');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: monthlyData.map(d => d.month),
                    datasets: [{
                        label: 'Attendance %',
                        data: monthlyData.map(d => d.percentage),
                        borderColor: '#6366f1',
                        backgroundColor: grad1,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Inter', weight: '600' },
                            bodyFont: { family: 'Inter' },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Inter', weight: '500' }, color: '#94a3b8' }, border: { display: false } },
                        y: { grid: { color: '#f1f5f9', drawBorder: false }, ticks: { font: { family: 'Inter' }, color: '#94a3b8' }, border: { display: false }, beginAtZero: true, max: 100 }
                    },
                    interaction: { intersect: false, mode: 'index' },
                }
            });
        }

        // Staff Status Doughnut Chart
        if (document.getElementById('statusChart')) {
            const ctx2 = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Pending', 'Rejected'],
                    datasets: [{
                        data: [{{ $approvedStaff }}, {{ $pendingStaff }}, {{ $rejectedStaff }}],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Inter', weight: '600' },
                            bodyFont: { family: 'Inter' },
                            padding: 12,
                            cornerRadius: 12,
                        }
                    }
                }
            });
        }
    </script>
    <script>
        let currentRecordId = null;

        function openRejectModal(attendanceId, staffName) {
            currentRecordId = attendanceId;
            document.getElementById('rejectAttendanceId').value = attendanceId;
            document.getElementById('rejectModalName').textContent = 'You are about to reject attendance for ' + staffName;
            document.getElementById('rejectReason').value = '';
            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentRecordId = null;
        }

        function submitReject() {
            const reason = document.getElementById('rejectReason').value;
            if (!reason.trim()) {
                alert('Please enter a rejection reason.');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/staff/attendance/' + currentRecordId + '/reject';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejected_reason';
            reasonInput.value = reason;

            form.appendChild(csrf);
            form.appendChild(reasonInput);
            document.body.appendChild(form);
            form.submit();
        }

        function updateStatusRow() {}
    </script>
@endpush
