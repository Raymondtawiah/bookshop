@extends('layouts.finance')

@section('title', 'Reports')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Reports</h1>
                <p class="page-subtitle">Monthly financial summaries</p>
            </div>
            <a href="{{ route('finance.reports.download', request()->query()) }}" class="add-book" style="background: #16a34a;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Download Word
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="color: #16a34a;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Monthly Income</p>
                    <p class="stat-value stat-value--compact" style="color: #16a34a;">${{ number_format($monthlyIncome, 2) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #dc2626;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2-4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Monthly Expenses</p>
                    <p class="stat-value stat-value--compact" style="color: #dc2626;">${{ number_format($monthlyExpenses, 2) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: {{ $profit >= 0 ? '#16a34a' : '#dc2626' }};">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Profit</p>
                    <p class="stat-value stat-value--compact" style="color: {{ $profit >= 0 ? '#16a34a' : '#dc2626' }};">${{ number_format($profit, 2) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #4f46e5;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Total Revenue</p>
                    <p class="stat-value stat-value--compact" style="color: #4f46e5;">${{ number_format($totalMonthlyRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="two-column">
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Generate Report</h2>
                </div>
                <div class="weekend-body">
                    <form method="GET" action="{{ route('finance.reports') }}" id="reportForm" class="space-y-4">
                        <div class="two-fields">
                            <div class="form-group">
                                <label>Month</label>
                                <div class="select-wrapper">
                                    <select name="month" class="w-full">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i)->format('F') }}</option>
                                        @endfor
                                    </select>
                                    <span class="select-arrow">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Year</label>
                                <div class="select-wrapper">
                                    <select name="year" class="w-full">
                                        @foreach(range(now()->year, now()->year - 5, -1) as $y)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                    <span class="select-arrow">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="actions">
                            <button type="submit" form="reportForm" class="publish" style="width: auto; padding: 0 30px;">Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Monthly Summary</h2>
                </div>
                <div class="weekend-body">
                    <p class="text-gray-600 mb-4">Showing report for {{ \Carbon\Carbon::create(null, $month)->format('F') }} {{ $year }}</p>
                    <div class="space-y-3">
                        <div class="event">
                            <div>
                                <div class="event-name">Income Sources</div>
                            </div>
                            <div class="event-right">
                                <span class="text-green-700 font-bold">${{ number_format($monthlyIncome, 2) }}</span>
                            </div>
                        </div>
                        <div class="event">
                            <div>
                                <div class="event-name">Expenses</div>
                            </div>
                            <div class="event-right">
                                <span class="text-red-700 font-bold">${{ number_format($monthlyExpenses, 2) }}</span>
                            </div>
                        </div>
                        <div class="event">
                            <div>
                                <div class="event-name">Net Profit</div>
                            </div>
                            <div class="event-right">
                                <span class="font-bold {{ $profit >= 0 ? 'text-green-700' : 'text-red-700' }}">${{ number_format($profit, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="weekend-note">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4"/>
                            <path d="M12 8h.01"/>
                        </svg>
                        Report is generated based on selected month and year.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
