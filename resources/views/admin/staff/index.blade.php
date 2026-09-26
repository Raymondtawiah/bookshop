@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Staff Management</h1>
                <p class="page-subtitle">Manage staff accounts, roles, and attendance.</p>
            </div>
            <a href="{{ route('admin.staff.create') }}" class="add-book">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Staff
            </a>
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="stat-label">Total Staff</p>
                        <p class="stat-value">{{ $totalStaff }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="stat-icon" style="color: #d97706;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="stat-label">Pending Approval</p>
                        <p class="stat-value">{{ $pendingStaff }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="stat-icon" style="color: #059669;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="stat-label">Approved Staff</p>
                        <p class="stat-value">{{ $approvedStaff }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="stat-icon" style="color: #dc2626;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <p class="stat-label">Rejected Staff</p>
                        <p class="stat-value">{{ $rejectedStaff }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="two-column">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Monthly Attendance Trend</div>
                        <p class="text-sm text-gray-500 mt-1">Last 7 months overview</p>
                    </div>
                </div>
                <div class="weekend-body">
                    <div class="relative h-64">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Staff Status</div>
                </div>
                <div class="weekend-body">
                    <div class="relative w-40 h-40 mb-6 mx-auto">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span class="text-sm font-medium text-gray-700">Present</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $approvedStaff }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                <span class="text-sm font-medium text-gray-700">Pending</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $pendingStaff }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span class="text-sm font-medium text-gray-700">Rejected</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $rejectedStaff }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ATTENDANCE RECORDS -->
        <div class="bottom-grid" style="margin-top: 24px;">
            <div class="panel" style="grid-column: 1 / -1;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Attendance Records</div>
                        <p class="text-sm text-gray-500 mt-1">Review and manage staff attendance submissions</p>
                    </div>
                </div>
                <div class="weekend-body" style="padding: 0;">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold">
                                <tr>
                                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Staff</th>
                                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Role</th>
                                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Date</th>
                                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Approved By</th>
                                    <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @if($attendances->count() > 0)
                                    @foreach($attendances as $record)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">{{ $record->user->initials() }}</div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $record->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $record->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
                                                {{ ucfirst(str_replace('_', ' ', $record->user->role)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($record->attendance_date)->format('M j, Y') }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $status = $record->status;
                                                $statusColors = ['present' => 'emerald', 'pending' => 'amber', 'rejected' => 'red'];
                                                $statusColor = $statusColors[$status] ?? 'gray';
                                            @endphp
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ optional($record->approver)->name ?? 'Admin' }}</td>
                                        <td class="px-6 py-4">
                                            @if($record->status === 'present')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600" title="Approved">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                            @elseif($record->status === 'pending')
                                                <div class="flex items-center justify-end gap-2">
                                                    <form action="{{ route('admin.staff.approve', $record->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="p-2 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        </button>
                                                    </form>
                                                    <button type="button" onclick="openRejectModal({{ $record->id }}, '{{ $record->user->name }}')" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Reject">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
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
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No attendance records found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALL STAFF -->
        <div class="panel" style="margin-top: 24px;">
            <div class="panel-header" style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div class="panel-title">All Staff</div>
                    <p class="text-sm text-gray-500 mt-1">Complete staff directory</p>
                </div>
            </div>
            <div class="weekend-body" style="padding: 0;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold">
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
                        <tbody class="divide-y divide-gray-100">
                            @if($staff->count() > 0)
                                @foreach($staff as $member)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">{{ $member->initials() }}</div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $member->name }}</p>
                                                <p class="text-xs text-gray-500">Staff</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $member->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $member->email }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 hidden md:table-cell">{{ $member->phone_number ?? '—' }}</td>
                                    <td class="px-6 py-4 text-gray-600 hidden lg:table-cell">{{ $member->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.staff.attendance', $member->id) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View Attendance History">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                            </a>
                                            @if(!$member->is_admin)
                                                <button type="button" onclick="openResetPasswordModal({{ $member->id }}, '{{ $member->name }}')" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Reset Password">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                                                </button>
                                                <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Delete Staff">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No staff members found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Attendance</h3>
            <p id="rejectModalName" class="text-sm text-gray-600 mb-4"></p>
            <input type="hidden" id="rejectAttendanceId" value="">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea id="rejectReason" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none" placeholder="Enter reason for rejection..."></textarea>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="submitReject()" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-lg transition-colors">Reject</button>
                <button onclick="closeRejectModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
            </div>
        </div>
    </div>

    {{-- Reset Password Modal --}}
    <div id="resetPasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Reset Password</h3>
            <p id="resetPasswordModalName" class="text-sm text-gray-600 mb-4"></p>
            <input type="hidden" id="resetPasswordUserId" value="">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="resetNewPassword" class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Enter new password">
                    <button type="button" onclick="toggleResetPassword('resetNewPassword', 'resetNewPasswordEye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="resetNewPasswordEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="resetConfirmPassword" class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Confirm new password">
                    <button type="button" onclick="toggleResetPassword('resetConfirmPassword', 'resetConfirmPasswordEye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="resetConfirmPasswordEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="submitResetPassword()" class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-lg transition-colors">Reset Password</button>
                <button onclick="closeResetPasswordModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
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

        function toggleResetPassword(fieldId, eyeId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(eyeId);
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l3.29 3.29m7.532 7.532l-3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                field.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        function openResetPasswordModal(userId, userName) {
            document.getElementById('resetPasswordUserId').value = userId;
            document.getElementById('resetPasswordModalName').textContent = 'You are about to reset password for ' + userName;
            document.getElementById('resetNewPassword').value = '';
            document.getElementById('resetConfirmPassword').value = '';
            const modal = document.getElementById('resetPasswordModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeResetPasswordModal() {
            const modal = document.getElementById('resetPasswordModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitResetPassword() {
            const userId = document.getElementById('resetPasswordUserId').value;
            const password = document.getElementById('resetNewPassword').value;
            const confirmPassword = document.getElementById('resetConfirmPassword').value;

            if (!password || password.length < 8) {
                alert('Password must be at least 8 characters.');
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/staff/' + userId + '/reset-password';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = password;

            const confirmInput = document.createElement('input');
            confirmInput.type = 'hidden';
            confirmInput.name = 'password_confirmation';
            confirmInput.value = confirmPassword;

            form.appendChild(csrf);
            form.appendChild(passwordInput);
            form.appendChild(confirmInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
