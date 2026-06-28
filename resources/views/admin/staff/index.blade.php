@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Staff Management</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Manage all staff accounts, approvals and attendance.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium mb-1">Total Staff</p>
            <p class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $totalStaff }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/30 dark:to-orange-800/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium mb-1">Pending Approval</p>
            <p class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $pendingStaff }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium mb-1">Approved Staff</p>
            <p class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $approvedStaff }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium mb-1">Rejected Staff</p>
            <p class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100">{{ $rejectedStaff }}</p>
        </div>
    </div>

    <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Staff Directory</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Manage all staff accounts, approvals and attendance.</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md flex items-center justify-center gap-1 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Staff
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
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
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span><span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Approved</span></div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Weekly Request Trend</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Requests submitted per day</p>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="requestTrendChart"></canvas>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-4 flex items-center justify-center">
            <canvas id="requestTrendChartMini"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50 text-zinc-600 dark:text-zinc-400 font-semibold">
                    <tr>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Profile</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Full Name</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Email</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap hidden md:table-cell">Phone</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap hidden lg:table-cell">Created</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap hidden xl:table-cell">Attendance %</th>
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
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 hidden md:table-cell">{{ $member->phone_number ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $status = $member->attendances()->latest()->first()?->status ?? 'pending';
                                    $statusColors = ['present' => 'emerald', 'pending' => 'amber', 'rejected' => 'red'];
                                    $statusColor = $statusColors[$status] ?? 'zinc';
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-400 ring-1 ring-{{ $statusColor }}-200 dark:ring-{{ $statusColor }}-700">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 hidden lg:table-cell">{{ $member->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 hidden xl:table-cell">
                                @php
                                    $totalAttendance = $member->attendances()->count();
                                    $presentCount = $member->attendances()->where('status', 'present')->count();
                                    $attendancePercent = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-zinc-100 dark:bg-zinc-700 rounded-full">
                                        <div class="h-2 rounded-full bg-{{ $statusColor }}-500 progress-bar" style="width:{{ $attendancePercent }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-zinc-700 dark:text-zinc-300 w-9">{{ $attendancePercent }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.staff.attendance', $member->id) }}" class="p-2 text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Attendance History">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002-2v12a2 2 0 002 2z"/></svg>
                                    </a>
                                    @php
                                        $latestAttendance = $member->attendances()->latest()->first();
                                        $hasApproved = $latestAttendance && $latestAttendance->status === 'present';
                                        $hasRejected = $latestAttendance && $latestAttendance->status === 'rejected';
                                    @endphp
                                    @if(!$hasApproved)
                                        <form action="{{ route('admin.staff.approve', $member->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if(!$hasRejected)
                                        <form action="{{ route('admin.staff.reject', $member->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Reject">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-zinc-500">No staff members found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Showing <span class="font-semibold text-zinc-900 dark:text-zinc-100">1-{{ $staff->count() }}</span> of <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $totalStaff }}</span> results</p>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-600 text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">Previous</button>
                <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold shadow-md">1</button>
                <button class="px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-600 text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">2</button>
                <button class="px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-600 text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">3</button>
                <button class="px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-600 text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">Next</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const commonOptions = {
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
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', weight: '500' }, color: '#94a3b8' },
                    border: { display: false }
                },
                y: {
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: { font: { family: 'Inter' }, color: '#94a3b8' },
                    border: { display: false },
                    beginAtZero: true,
                }
            },
            interaction: { intersect: false, mode: 'index' },
        };

        if (document.getElementById('attendanceChart')) {
            const ctx1 = document.getElementById('attendanceChart').getContext('2d');
            const grad1 = ctx1.createLinearGradient(0, 0, 0, 250);
            grad1.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
            grad1.addColorStop(1, 'rgba(99, 102, 241, 0)');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Attendance %',
                        data: [82, 85, 83, 88, 86, 89, 87],
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
                options: commonOptions
            });
        }

        if (document.getElementById('statusChart')) {
            const ctx2 = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Approved', 'Pending', 'Rejected'],
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

        if (document.getElementById('requestTrendChart')) {
            const ctx3 = document.getElementById('requestTrendChart').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Requests',
                        data: [12, 19, 15, 25, 22, 18, 21],
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(99, 102, 241, 0.4)',
                            'rgba(99, 102, 241, 0.4)',
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: commonOptions
            });
        }

        if (document.getElementById('requestTrendChartMini')) {
            const ctx4 = document.getElementById('requestTrendChartMini').getContext('2d');
            new Chart(ctx4, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Requests',
                        data: [12, 19, 15, 25, 22, 18, 21],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },
                    elements: { line: { borderWidth: 2 } }
                }
            });
        }
    </script>
@endsection