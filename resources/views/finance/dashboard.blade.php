@extends('layouts.finance')

@section('title', 'Finance Dashboard')

@section('content')
    <!-- Finance Navigation -->
    <div class="flex items-center justify-between gap-2 mb-6">
        <nav class="flex flex-wrap gap-2">
            <a href="{{ route('finance.dashboard') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.dashboard') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
            <a href="{{ route('finance.incomes') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.incomes*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Income</a>
            <a href="{{ route('finance.expenses') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.expenses*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Expenses</a>
            <a href="{{ route('finance.payments') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.payments*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Payments</a>
            <a href="{{ route('finance.reports') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.reports*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Reports</a>
            <a href="{{ route('finance.settings') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.settings*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Settings</a>
            <a href="{{ route('finance.attendance') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.attendance*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Attendance</a>
        </nav>
        <button onclick="markAttendance()" class="px-4 py-2 text-sm font-medium rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 transition-colors flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            My Attendance
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p id="total-revenue" class="text-2xl font-bold mt-1 text-gray-900">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Expenses</p>
                    <p id="total-expenses" class="text-2xl font-bold mt-1 text-gray-900">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-4a3 3 0 013-3h2a3 3 0 013 3v4M3 10h18M7 15h1m-1 4h1m4-4h1m-1 4h1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Net Profit</p>
                    <p id="net-profit" class="text-2xl font-bold mt-1 text-gray-900">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h4a2 2 0 002-2zm0 0V9a2 2 0 012-2h4a2 2 0 012 2v10m-6 0h6a2 2 0 002-2v-3a2 2 0 00-2-2h-6a2 2 0 00-2 2v3a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Payments</p>
                    <p id="pending-payments" class="text-2xl font-bold mt-1 text-gray-900">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('finance.incomes') }}" class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-emerald-200 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Income</h3>
                    <p class="text-sm text-gray-500">Add and view revenue</p>
                </div>
            </div>
        </a>

        <a href="{{ route('finance.expenses') }}" class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-red-200 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-4a3 3 0 013-3h2a3 3 0 013 3v4M3 10h18M7 15h1m-1 4h1m4-4h1m-1 4h1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Expenses</h3>
                    <p class="text-sm text-gray-500">Record business expenses</p>
                </div>
            </div>
        </a>

        <a href="{{ route('finance.payments') }}" class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-blue-200 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m-1 4h1m4-4h1m-1 4h1m-5 10v-4a3 3 0 013-3h2a3 3 0 013 3v4M3 10h18"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Payments</h3>
                    <p class="text-sm text-gray-500">Transaction history</p>
                </div>
            </div>
        </a>

        <a href="{{ route('finance.reports') }}" class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-purple-200 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m0 0v-2m0 2h2m-2 0h-2m2 2v2m0-2v-2m0 2h2m-2 0h-2m-4-8h10M5 12h14M5 5h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Reports</h3>
                    <p class="text-sm text-gray-500">Monthly summaries</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Request Finance Action -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Request Finance Action</h2>
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="request-type" placeholder="Request Type (e.g., Reimbursement)" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            <input type="text" id="request-details" placeholder="Details" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            <button onclick="submitFinanceRequest()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Submit Request</button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    async function loadDashboard() {
        try {
            const response = await fetch('{{ route("finance.dashboard-data") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('total-revenue').textContent = '$' + data.data.total_revenue.toFixed(2);
                document.getElementById('total-expenses').textContent = '$' + data.data.total_expenses.toFixed(2);
                document.getElementById('net-profit').textContent = '$' + data.data.net_profit.toFixed(2);
                document.getElementById('pending-payments').textContent = '$' + data.data.pending_payments.toFixed(2);
            }
        } catch (error) {
            console.error('Error loading dashboard:', error);
        }
    }

    async function submitFinanceRequest() {
        const type = document.getElementById('request-type').value;
        const details = document.getElementById('request-details').value;
        
        if (!type || !details) {
            alert('Please fill in both type and details');
            return;
        }
        
        const response = await fetch('{{ route('finance.requests.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ type, details }),
        });
        
        const data = await response.json();
        if (data.success) {
            alert('Request submitted successfully!');
            document.getElementById('request-type').value = '';
            document.getElementById('request-details').value = '';
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
        } else {
            alert(data.message || 'Failed to mark attendance.');
        }
    }

    loadDashboard();
</script>
@endpush