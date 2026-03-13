<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Shopping Cart - Bookshop</title>
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
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
                            
                            <!-- User Menu with Dropdown -->
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
                        @endauth
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
                </div>
            </div>
        </nav>

        <!-- Cart Content -->
        <div class="max-w-7xl mx-auto px-6 py-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>
            
            @if($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                            <div class="bg-white rounded-lg shadow-md p-6 flex items-center gap-4">
                                <div class="w-20 h-24 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                    @if($item->book && $item->book->cover_image)
                                        <img src="{{ Storage::url($item->book->cover_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('welcome.jpg') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $item->product_name }}</h3>
                                    <p class="text-gray-600">₵{{ number_format($item->product_price, 2) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 px-2 py-1 border rounded text-center quantity-input" onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">₵{{ number_format($item->product_price * $item->quantity, 2) }}</p>
                                    <button type="button" onclick="openDeleteModal{{ $item->id }}()" class="text-sm text-red-600 hover:underline">Remove</button>
                                </div>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <x-modal-delete
                                :id="$item->id"
                                :action="route('cart.remove', $item->id)"
                                title="Remove Item"
                                message="Are you sure you want to remove this item from your cart?"
                                confirmText="Remove"
                            />
                        @endforeach
                    </div>

                    <!-- Cart Summary -->
                    <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>₵{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span>₵0.00</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span>₵{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('checkout') }}" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-xl text-gray-600 mb-4">Your cart is empty</p>
                    <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Continue Shopping</a>
                </div>
            @endif
        </div>
    </body>
</html>