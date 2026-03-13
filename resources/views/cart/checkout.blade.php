<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Checkout - Bookshop</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50 antialiased">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Bookshop
                    </a>
                    
                    <!-- User Menu with Dropdown for authenticated users -->
                    @auth
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600 font-medium">
                            Dashboard
                        </a>
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        document.getElementById('user-menu-button')?.addEventListener('click', function() {
                            document.getElementById('user-dropdown').classList.toggle('hidden');
                        });

                        // Close dropdown when clicking outside
                        document.addEventListener('click', function(event) {
                            var dropdown = document.getElementById('user-dropdown');
                            var button = document.getElementById('user-menu-button');
                            if (dropdown && button && !dropdown.classList.contains('hidden') && !button.contains(event.target) && !dropdown.contains(event.target)) {
                                dropdown.classList.add('hidden');
                            }
                        });
                    </script>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Checkout Content -->
        <div class="max-w-3xl mx-auto px-6 py-12">
            @if(isset($order) && $order)
                <!-- Order Success -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
                        <p class="text-gray-600">Thank you for your purchase. Your order has been placed.</p>
                    </div>

                    <div class="border-t pt-6">
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Total Amount Paid:</span>
                            <span class="font-bold text-indigo-600">₵{{ number_format($total, 2) }}</span>
                        </div>
                        @if($order->customer_name)
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Personalized For:</span>
                            <span class="font-bold text-gray-900">{{ $order->customer_name }}</span>
                        </div>
                        @endif
                    </div>

                    @if($order->personalized_pdf_path)
                    <div class="mt-6 bg-indigo-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-indigo-900">Your Personalized Book is Ready!</h3>
                                <p class="text-sm text-indigo-700">Click the button below to download your personalized PDF with your name as watermark.</p>
                            </div>
                            <a href="{{ route('order.download', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="mt-8 text-center">
                        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            @else
                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>
                    
                    <!-- Order Summary -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                        <div class="space-y-3">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between items-center py-2 border-b">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="font-medium">₵{{ number_format($item->product_price * $item->quantity, 2) }}</p>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-lg font-bold text-indigo-600">₵{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Customer Name Form -->
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        @if($hasPdfTemplate)
                        <div class="mb-6 bg-indigo-50 rounded-lg p-4">
                            <h3 class="font-semibold text-indigo-900 mb-2">📖 Personalized Book</h3>
                            <p class="text-sm text-indigo-700 mb-4">This order includes a PDF book. Please enter the name to personalize the book with.</p>
                            
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Name for Personalization *</label>
                                <input type="text" name="customer_name" id="customer_name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter name to appear on the book">
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">The name will be added as a watermark on every page of the PDF.</p>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="customer_name" value="Customer">
                        @endif

                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                            Confirm Order
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </body>
</html>
