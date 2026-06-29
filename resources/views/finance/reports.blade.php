@extends('layouts.finance')

@section('title', 'Reports - Finance')

@section('content')
    <!-- Finance Sidebar -->
    <nav class="flex space-x-4 mb-6 overflow-x-auto">
        <a href="{{ route('finance.dashboard') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.dashboard') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
        <a href="{{ route('finance.incomes') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.incomes*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Income</a>
        <a href="{{ route('finance.expenses') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.expenses*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Expenses</a>
        <a href="{{ route('finance.payments') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.payments*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Payments</a>
        <a href="{{ route('finance.reports') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.reports*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Reports</a>
        <a href="{{ route('finance.settings') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.settings*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Settings</a>
    </nav>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex items-end gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                <select id="report-month" onchange="loadReport()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                <select id="report-year" onchange="loadReport()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    @for($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <p class="text-sm text-gray-500 mb-2">Monthly Income</p>
                <p id="monthly-income" class="text-3xl font-bold text-gray-900">$0.00</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <p class="text-sm text-gray-500 mb-2">Monthly Expenses</p>
                <p id="monthly-expenses" class="text-3xl font-bold text-gray-900">$0.00</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <p class="text-sm text-gray-500 mb-2">Profit</p>
                <p id="profit" class="text-3xl font-bold text-gray-900">$0.00</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Income by Source</h2>
        </div>
        <div class="p-6">
            <canvas id="incomeChart" height="100"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    async function loadReport() {
        const month = document.getElementById('report-month').value;
        const year = document.getElementById('report-year').value;

        const response = await fetch(`{{ route('api.finance.reports') }}?month=${month}&year=${year}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            document.getElementById('monthly-income').textContent = '$' + data.data.monthly_income.toFixed(2);
            document.getElementById('monthly-expenses').textContent = '$' + data.data.monthly_expenses.toFixed(2);
            document.getElementById('profit').textContent = '$' + data.data.profit.toFixed(2);
        }
    }

    loadReport();
</script>
@endpush