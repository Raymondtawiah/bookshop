@extends('layouts.finance')

@section('title', 'Finance Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Finance Dashboard</h1>
        <p class="text-gray-600">Overview of financial performance</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Expenses</p>
            <p class="text-2xl font-bold text-red-600">${{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Net Profit</p>
            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">${{ number_format($netProfit, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500 mb-1">Pending Payments</p>
            <p class="text-2xl font-bold text-yellow-600">${{ number_format($pendingPayments, 2) }}</p>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Revenue Breakdown</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Income</p>
                <p class="text-lg font-bold text-gray-900">${{ number_format($incomeTotal, 2) }}</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Orders</p>
                <p class="text-lg font-bold text-gray-900">${{ number_format($orderTotal, 2) }}</p>
            </div>
            <div class="p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Webinars</p>
                <p class="text-lg font-bold text-gray-900">${{ number_format($webinarTotal, 2) }}</p>
            </div>
            <div class="p-4 bg-orange-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Coaching</p>
                <p class="text-lg font-bold text-gray-900">${{ number_format($coachingTotal, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('finance.income') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Add Income</h3>
                    <p class="text-sm text-gray-500">Record new income</p>
                </div>
            </div>
        </a>
        <a href="{{ route('finance.expenses') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2-4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Add Expense</h3>
                    <p class="text-sm text-gray-500">Record new expense</p>
                </div>
            </div>
        </a>
        <a href="{{ route('finance.reports') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">View Reports</h3>
                    <p class="text-sm text-gray-500">Monthly summaries</p>
                </div>
            </div>
        </a>
    </div>
@endsection
