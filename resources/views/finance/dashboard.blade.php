@extends('layouts.finance')

@section('title', 'Finance Dashboard')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Finance Dashboard</h1>
                <p class="page-subtitle">Overview of financial performance</p>
            </div>
        </div>

        <div class="finance-dashboard">
            <div class="finance-hero">
                <div class="finance-hero-item">
                    <span class="finance-hero-label">Total Revenue</span>
                    <span class="finance-hero-value">${{ number_format($totalRevenue, 2) }}</span>
                </div>
                <div class="finance-hero-item">
                    <span class="finance-hero-label">Total Expenses</span>
                    <span class="finance-hero-value finance-hero-value--red">${{ number_format($totalExpenses, 2) }}</span>
                </div>
                <div class="finance-hero-item">
                    <span class="finance-hero-label">Net Profit</span>
                    <span class="finance-hero-value {{ $netProfit >= 0 ? 'finance-hero-value--green' : 'finance-hero-value--red' }}">
                        ${{ number_format($netProfit, 2) }}
                    </span>
                </div>
                <div class="finance-hero-item">
                    <span class="finance-hero-label">Pending Payments</span>
                    <span class="finance-hero-value finance-hero-value--amber">${{ number_format($pendingPayments, 2) }}</span>
                </div>
            </div>

            <div class="panel mb-6">
                <div class="panel-header">
                    <h2 class="panel-title">Revenue Breakdown</h2>
                </div>
                <div class="panel-body">
                    <div class="revenue-grid">
                        <div class="revenue-card">
                            <span class="revenue-label">Income</span>
                            <span class="revenue-value">${{ number_format($incomeTotal, 2) }}</span>
                        </div>
                        <div class="revenue-card">
                            <span class="revenue-label">Orders</span>
                            <span class="revenue-value">${{ number_format($orderTotal, 2) }}</span>
                        </div>
                        <div class="revenue-card">
                            <span class="revenue-label">Webinars</span>
                            <span class="revenue-value">${{ number_format($webinarTotal, 2) }}</span>
                        </div>
                        <div class="revenue-card">
                            <span class="revenue-label">Coaching</span>
                            <span class="revenue-value">${{ number_format($coachingTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="finance-actions">
                <a href="{{ route('finance.income') }}" class="finance-action">
                    <div class="finance-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="finance-action-title">Add Income</h3>
                        <p class="finance-action-subtitle">Record new income</p>
                    </div>
                </a>
                <a href="{{ route('finance.expenses') }}" class="finance-action">
                    <div class="finance-action-icon finance-action-icon--red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2-4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="finance-action-title">Add Expense</h3>
                        <p class="finance-action-subtitle">Record new expense</p>
                    </div>
                </a>
                <a href="{{ route('finance.reports') }}" class="finance-action">
                    <div class="finance-action-icon finance-action-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="finance-action-title">View Reports</h3>
                        <p class="finance-action-subtitle">Monthly summaries</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
