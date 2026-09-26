@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Order Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h2 class="text-xl font-bold text-white">Order #{{ $order->order_number ?? $order->id }}</h2>
                    <p class="text-indigo-100 text-sm mt-1">Placed on {{ $order->created_at->format('M j, Y g:i A') }}</p>
                </div>
                <span class="px-3 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap
                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($order->payment_status ?? 'pending') }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Information -->
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Customer</h3>
                    </div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $order->customer_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $order->contact ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nationality</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $order->nationality ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Location</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $order->residence ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Payment Information -->
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Payment</h3>
                    </div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Method</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                @if($order->payment_method === 'paystack')
                                    Paystack
                                @elseif($order->payment_method === 'momo')
                                    Mobile Money
                                @elseif($order->payment_method === 'bank')
                                    Bank Transfer
                                @else
                                    Card Payment
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</dt>
                            <dd class="text-2xl font-bold text-indigo-600 mt-1">
                                @if($order->currency === 'GHS')
                                    ₵{{ number_format($order->total_amount, 2) }}
                                @else
                                    ${{ number_format($order->total_amount_usd ?? $order->total_amount, 2) }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Actions</h3>
                    </div>
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-700 mb-1.5">Order Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="payment_status" class="block text-xs font-medium text-gray-700 mb-1.5">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-semibold transition-colors">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ordered Books -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Ordered Books</h3>
        </div>
        <div class="p-6">
            @php
                $items = $order->order_items;
            @endphp
            @if(!empty($items) && $items->count() > 0)
            <div class="bg-gray-50 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book ID</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ is_array($item) ? ($item['product_name'] ?? 'Unknown') : ($item->product_name ?? 'Unknown') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $bookId = is_array($item) ? ($item['book_id'] ?? null) : ($item->book_id ?? null);
                                    @endphp
                                    @if($bookId)
                                        <span class="text-xs text-gray-500">#{{ $bookId }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <p class="text-gray-500 text-sm">No order items found.</p>
            @endif
        </div>
    </div>

    @if($order->payment_status === 'pending')
    <!-- Payment Reminder -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
            <h3 class="text-lg font-semibold text-gray-900">Payment Reminder</h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">Send a payment reminder to the customer so they can complete their payment.</p>
            <form action="{{ route('admin.orders.sendPaymentReminder', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-semibold transition-colors">
                    Send Payment Reminder
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
