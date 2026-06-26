@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Orders</h1>
        <p class="text-gray-600">Manage customer e-book orders and payments</p>
    </div>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l-1 7a2 2 0 01-2 2H7a2 2 0 01-2-2l-1-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Paid Orders</p>
                    <p class="text-2xl font-bold text-green-600">{{ $totalPaid }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Pending Orders</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $totalPending }}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl shadow-lg border border-indigo-100 p-4 sm:p-6 mb-8">
        <form method="GET" action="{{ route('admin.orders') }}" class="space-y-4 sm:space-y-6">
            <!-- Search Bar -->
            <div>
                <label for="search" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Search Orders</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Search by order #, name, email, or phone..."
                        class="w-full pl-10 pr-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all"
                        value="{{ request()->get('search') }}">
                </div>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label for="payment_status_filter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
                    <select name="payment_status" id="payment_status_filter" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All Payment Statuses</option>
                        <option value="paid" {{ request()->get('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request()->get('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request()->get('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label for="status_filter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Order Status</label>
                    <select name="status" id="status_filter" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All Order Statuses</option>
                        <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request()->get('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request()->get('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="delivered" {{ request()->get('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request()->get('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="date_range" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Date Range</label>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <input type="date" name="start_date" id="start_date" class="flex-1 px-3 py-2 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all" value="{{ request()->get('start_date') }}">
                        <input type="date" name="end_date" id="end_date" class="flex-1 px-3 py-2 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all" value="{{ request()->get('end_date') }}">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                <button type="submit" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all font-semibold shadow-md text-sm">
                    Apply Filters
                </button>
                <a href="{{ route('admin.orders') }}" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 text-gray-600 hover:text-gray-900 hover:bg-white rounded-lg transition-all font-semibold text-center shadow-sm text-sm">
                    Reset
                </a>
            </div>
        </form>
        </div>
        <!-- Orders Chart (Round) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col items-center">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Orders Overview</h2>
            <div class="w-full max-w-md">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-base sm:text-lg font-semibold text-white">Order List</h2>
            <p class="text-indigo-100 text-xs sm:text-sm">Click on any row to view details or manage order</p>
        </div>
        @if($orders->isNotEmpty())
            <table class="w-full table-fixed">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order #</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Book(s)</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-24">Amount</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-24">Discount</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Payment</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-20">Date</th>
                        <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-2 py-3">
                            <span class="font-semibold text-gray-900 text-xs">#{{ $order->order_number ?? $order->id }}</span>
                        </td>
                        <td class="px-2 py-3 text-xs text-gray-600 truncate">
                            {{ $order->customer_name ?? '-' }}
                        </td>
                        <td class="px-2 py-3">
                            @php
                                $items = $order->order_items;
                                if ($items instanceof \Illuminate\Support\Collection) {
                                    $itemsArray = $items->toArray();
                                } elseif (is_array($items)) {
                                    $itemsArray = $items;
                                } else {
                                    $itemsArray = [];
                                }
                            @endphp
                            @if(count($itemsArray) > 0)
                                <div class="font-semibold text-gray-900 text-xs truncate">
                                    @foreach($itemsArray as $item)
                                        @php
                                            $name = is_array($item) ? ($item['product_name'] ?? 'Unknown') : ($item->product_name ?? 'Unknown');
                                        @endphp
                                        {{ \Illuminate\Support\Str::limit($name, 25) }}@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                                <div class="text-xs text-gray-500">Qty: {{ array_sum(array_column($itemsArray, 'quantity')) }}</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-2 py-3 text-xs text-gray-600">
                            ${{ number_format($order->total_amount_usd ?? $order->total_amount, 2) }}
                        </td>
                        <td class="px-2 py-3 text-xs text-gray-600">
                            @if($order->discount_code)
                                <span class="text-green-600 font-medium">{{ $order->discount_code }}</span>
                                <div class="text-xs text-gray-500">-${{ number_format($order->discount_amount ?? 0, 2) }}</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-2 py-3 whitespace-nowrap">
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">Paid</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                            @else
                                <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700">{{ ucfirst($order->payment_status ?? 'failed') }}</span>
                            @endif
<div class="text-xs text-gray-500 mt-1">
                                @if($order->payment_method === 'paystack')
                                    Paystack
                                @elseif($order->payment_method === 'momo')
                                    Mobile Money
                                @elseif($order->payment_method === 'bank')
                                    Bank Transfer
                                @else
                                    Card
                                @endif
                            </div>
                        </td>
                        <td class="px-2 py-3 whitespace-nowrap">
                            @if($order->status === 'delivered' || $order->status === 'completed')
                                <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">Completed</span>
                            @elseif($order->status === 'pending')
                                <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                            @elseif($order->status === 'processing')
                                <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-700">Processing</span>
                            @else
                                <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-700">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td class="px-2 py-3 text-xs text-gray-600">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-2 py-3 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-800" title="View Order">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l-1 7a2 2 0 01-2 2H7a2 2 0 01-2-2l-1-7z"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-semibold">No orders found</p>
                <p class="text-gray-400 text-sm mt-1">Orders will appear here when customers make purchases</p>
            </div>
        @endif
    </div>
</div>

@php
    $chartData = [
        'labels' => ['Total Orders', 'Paid Orders', 'Pending Orders'],
        'values' => [$totalOrders, $totalPaid, $totalPending],
    ];
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ordersChart');
        if (!ctx) return;

        const chart = @json($chartData);

        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: chart.labels,
                datasets: [{
                    label: 'Orders',
                    data: chart.values,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                    ],
                    borderWidth: 1,
                    hoverOffset: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
            },
        });
    });
</script>

@endsection