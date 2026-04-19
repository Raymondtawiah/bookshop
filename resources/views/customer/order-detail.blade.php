<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - {{ config('app.name', 'Visa Resources') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico" sizes="any">
</head>
<body class="bg-gray-50 font-sans">
    <x-customer-navbar />

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-8">
        <div class="mb-6">
            <a href="{{ route('my-orders') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Orders
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 sm:px-6 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Order #{{ $order->order_number ?? $order->id }}</h2>
                    <span class="mt-2 sm:mt-0 px-2 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap
                        @if($order->status === 'delivered') bg-green-100 text-green-800
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                        @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="px-4 py-4 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Customer Information</h3>
                        <dl class="space-y-2 sm:space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-gray-900">{{ $order->customer_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-gray-900">{{ $order->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Contact</dt>
                                <dd class="text-gray-900">{{ $order->contact }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nationality</dt>
                                <dd class="text-gray-900">{{ $order->nationality ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Delivery Address</dt>
                                <dd class="text-gray-900">{{ $order->residence ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                <dd class="text-gray-900">
                                    @if($order->payment_method === 'momo')
                                        Mobile Money
                                    @elseif($order->payment_method === 'bank')
                                        Bank Transfer
                                    @else
                                        Card Payment
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                <dd class="text-gray-900">
                                    @if($order->payment_status === 'paid')
                                        <span class="text-green-600 font-medium">Paid</span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="text-yellow-600 font-medium">Pending</span>
                                    @else
                                        <span class="text-red-600 font-medium">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                <dd class="text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                @php($exchangeRate = $order->exchange_rate ?? config('settings.usd_to_ghs_rate', 12.50))
                                @php($amountGhs = $order->total_amount_ghs ?? ($order->total_amount * $exchangeRate))
                                <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                                <dd class="text-xl font-bold text-indigo-600">${{ number_format($amountGhs, 2) }}</dd>
                                <dd class="text-sm text-gray-500">(${{ number_format($order->total_amount, 2) }} @ {{ $exchangeRate }})</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ordered Books</h3>
                    @php
                        $items = $order->order_items;
                    @endphp
                    @if(!empty($items) && $items->count() > 0)
                    <div class="bg-gray-50 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Book</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Price</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Qty</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($items as $item)
                                <tr>
                                    <td class="px-2 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ is_array($item) ? ($item['product_name'] ?? 'Unknown') : 'Unknown' }}</div>
                                    </td>
                                    <td class="px-2 py-3 text-sm text-gray-900 whitespace-nowrap">${{ number_format(is_array($item) ? ($item['unit_price_usd'] ?? $item['product_price'] ?? 0) : ($item->unit_price_usd ?? $item->product_price ?? 0), 2) }}</td>
                                    <td class="px-2 py-3 text-sm text-gray-900 whitespace-nowrap">{{ is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1) }}</td>
                                    <td class="px-2 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">${{ number_format((is_array($item) ? ($item['unit_price_usd'] ?? $item['product_price'] ?? 0) : ($item->unit_price_usd ?? $item->product_price ?? 0)) * (is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1)), 2) }}</td>
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
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Bookshop. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>