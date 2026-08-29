@extends('layouts.finance')

@section('title', 'Reports')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Reports</h1>
        <p class="text-gray-600">Monthly financial summaries</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('finance.reports') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                <select name="month" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select name="year" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @foreach(range(now()->year, now()->year - 5, -1) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Generate Report</button>
            <a href="{{ route('finance.reports.download', request()->query()) }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-center">Download Word</a>
        </form>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Monthly Income</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($monthlyIncome, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Monthly Expenses</p>
            <p class="text-2xl font-bold text-red-600">${{ number_format($monthlyExpenses, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Profit</p>
            <p class="text-2xl font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">${{ number_format($profit, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($totalMonthlyRevenue, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Monthly Summary</h2>
        <p class="text-gray-600 mb-4">Showing report for {{ \Carbon\Carbon::create(null, $month)->format('F') }} {{ $year }}</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-4 bg-green-50 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Income Sources</h3>
                <p class="text-2xl font-bold text-green-600">${{ number_format($monthlyIncome, 2) }}</p>
            </div>
            <div class="p-4 bg-red-50 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Expenses</h3>
                <p class="text-2xl font-bold text-red-600">${{ number_format($monthlyExpenses, 2) }}</p>
            </div>
        </div>
    </div>
@endsection
