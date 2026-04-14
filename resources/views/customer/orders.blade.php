<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>My Orders - {{ config('app.name', 'Visa Resources') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 font-sans">
        <x-customer-navbar />
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-8">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
                    <p class="text-gray-500">View your order history</p>
                </div>
                <a href="{{ route('home') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Orders List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @if($orders->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <a href="{{ route('my-order.show', $order->id) }}" class="block p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">₵{{ number_format($order->total_amount, 2) }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                @php
                                    $orderItems = $order->order_items ?? [];
                                    $items = is_array($orderItems) ? collect($orderItems) : collect([]);
                                @endphp
                                @if($items->count() > 0)
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Ordered Books:</h4>
                                    <div class="space-y-2">
                                        @foreach($items as $item)
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-900">{{ is_array($item) ? ($item['product_name'] ?? 'Unknown') : 'Unknown' }}</span>
                                            <span class="text-gray-500">x{{ is_array($item) ? ($item['quantity'] ?? 1) : 1 }}</span>
                                            <span class="font-medium text-gray-900">₵{{ number_format((is_array($item) ? ($item['product_price'] ?? 0) : 0) * (is_array($item) ? ($item['quantity'] ?? 1) : 1), 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                            </a>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-4">No orders yet. Start shopping!</p>
                        <a href="{{ route('home') }}" class="inline-block px-4 py-2 text-indigo-600 hover:text-indigo-700 font-medium">
                            Browse Books →
                        </a>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Bookshop. All rights reserved.</p>
            </div>
        </footer>

        <x-chat-widget />
    </body>
</html>
