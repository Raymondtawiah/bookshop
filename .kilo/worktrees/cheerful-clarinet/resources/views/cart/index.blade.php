<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Shopping Cart - Bookshop</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 antialiased">
        <x-flash-message />
        <x-customer-navbar />

        <!-- Cart Content -->
        <div class="max-w-7xl mx-auto px-4 md:px-6 pt-24 pb-12">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 md:mb-8">Shopping Cart</h1>
            
            @if($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <div class="w-20 h-24 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                    @if($item->book && $item->book->cover_image)
                                        <img src="{{ $item->book->cover_image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('welcome.jpg') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 w-full">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $item->product_name }}</h3>
                                    <p class="text-gray-600 text-sm md:text-base">₵{{ number_format($item->product_price, 2) }}</p>
                                </div>
                                <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 px-2 py-1 border rounded text-center quantity-input" onchange="this.form.submit()">
                                    </form>
                                    <button type="button" onclick="openDeleteModal{{ $item->id }}()" class="text-sm text-red-600 hover:underline whitespace-nowrap">Remove</button>
                                </div>
                                <div class="text-left sm:text-right w-full sm:w-auto">
                                    <p class="text-lg font-bold text-gray-900">₵{{ number_format($item->product_price * $item->quantity, 2) }}</p>
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
                    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 h-fit">
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
        <x-customer-footer />

        <x-chat-widget />
    </body>
</html>