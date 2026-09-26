@extends('layouts.finance')

@section('title', 'Payments')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Payments</h1>
                <p class="page-subtitle">Transaction history across all channels</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Total Payments</p>
                    <p class="stat-value stat-value--compact">{{ $payments->count() }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #16a34a;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Completed</p>
                    <p class="stat-value stat-value--compact" style="color: #16a34a;">{{ $payments->where('status', 'paid')->count() }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #d97706;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Pending</p>
                    <p class="stat-value stat-value--compact" style="color: #d97706;">{{ $payments->where('status', 'pending')->count() }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #dc2626;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Failed</p>
                    <p class="stat-value stat-value--compact" style="color: #dc2626;">{{ $payments->where('status', 'failed')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Payment Transactions</h2>
                <form method="GET" action="{{ route('finance.payments') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="select-wrapper">
                        <select name="type" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="orders" {{ $type === 'orders' ? 'selected' : '' }}>Orders</option>
                            <option value="webinars" {{ $type === 'webinars' ? 'selected' : '' }}>Webinars</option>
                            <option value="coaching" {{ $type === 'coaching' ? 'selected' : '' }}>Coaching</option>
                        </select>
                        <span class="select-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </span>
                    </div>
                </form>
            </div>
            <div class="weekend-body" style="padding: 0;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Type</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Reference</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Customer</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Amount</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-900">{{ $payment['type'] }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $payment['reference'] }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $payment['customer'] }}</td>
                                    <td class="px-6 py-4 text-gray-900 font-medium">{{ $payment['currency'] === 'GHS' ? '₵' : '$' }}{{ number_format($payment['amount'], 2) }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $status = strtolower($payment['status'] ?? 'pending');
                                            $statusColors = ['paid' => 'bg-green-100 text-green-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'failed' => 'bg-red-100 text-red-700'];
                                            $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                            {{ ucfirst($payment['status'] ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($payment['date'])->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No payments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
