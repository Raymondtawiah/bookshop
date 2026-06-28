@extends('layouts.finance')

@section('title', 'My Attendance')

@section('content')
<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Attendance</h1>
        <p class="mt-1 text-sm text-gray-500">View your attendance history and mark your presence.</p>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Attendance Trend</h2>
                <p class="text-sm text-gray-500">Last 30 days overview</p>
            </div>
            <button onclick="markAttendance()" class="px-4 py-2 text-sm font-medium rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">Mark Today's Attendance</button>
        </div>
        <div class="relative h-64">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="attendance-tbody">
                    <tr><td colspan="2" class="px-6 py-8 text-center text-gray-500">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    async function loadAttendance() {
        const response = await fetch('{{ route("finance.attendance-data") }}', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });
        const data = await response.json();
        const tbody = document.getElementById('attendance-tbody');

        if (data.success && data.data.length > 0) {
            const labels = data.data.map(r => new Date(r.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })).reverse();
            const statuses = data.data.map(r => r.status === 'present' || r.status === 'approved' ? 1 : 0).reverse();
            const colors = data.data.map(r => r.status === 'present' || r.status === 'approved' ? '#10b981' : r.status === 'rejected' ? '#ef4444' : '#f59e0b').reverse();

            tbody.innerHTML = data.data.map(r => `
                <tr>
                    <td class="px-6 py-4 text-gray-900 font-medium">${new Date(r.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold ${r.status === 'present' || r.status === 'approved' ? 'bg-emerald-100 text-emerald-700' : r.status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'}">
                            ${r.status === 'present' ? 'Present' : r.status === 'approved' ? 'Approved' : r.status === 'rejected' ? 'Rejected' : 'Pending'}
                        </span>
                    </td>
                </tr>
            `).join('');

            if (window.attendanceChart) window.attendanceChart.destroy();
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            window.attendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Attendance',
                        data: statuses,
                        backgroundColor: colors,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Inter', weight: '500' }, color: '#94a3b8' }, border: { display: false } },
                        y: { grid: { color: '#f1f5f9', drawBorder: false }, ticks: { display: false }, border: { display: false }, beginAtZero: true, max: 1 }
                    }
                }
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="2" class="px-6 py-12 text-center text-gray-500">No attendance records found.</td></tr>';
        }
    }

    async function markAttendance() {
        const response = await fetch('{{ route("api.attendance.mark") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        });

        const data = await response.json();
        if (data.success) {
            alert(data.message || 'Attendance marked successfully!');
            loadAttendance();
        } else {
            alert(data.message || 'Failed to mark attendance.');
        }
    }

    loadAttendance();
</script>
@endpush
