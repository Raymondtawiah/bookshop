<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4f46e5" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="BookShop" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <title>Checkout - {{ config('app.name', 'Bookshop') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/favicon.ico">
</head>
<body class="antialiased overflow-x-hidden m-0 p-0 box-border w-full min-w-0">
    <x-flash-message />
    <x-customer-navbar />

    <div class="w-full overflow-x-hidden min-w-0 mx-0 px-0">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-900 mt-10">Checkout</h1>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-2">Order Summary</h2>
                    @if(isset($direct) && $direct && isset($book))
                        <div class="flex justify-between items-center py-1 border-b">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $book->title }}</p>
                            </div>
                            <p class="font-medium text-sm">${{ number_format($book->price, 2) }}</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between items-center py-1 border-b">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $item->product_name }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                 <p class="font-medium text-sm">${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                            </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center mt-2 pt-2 border-t">
                        <span class="text-sm font-bold">Total</span>
                        <span class="text-sm font-bold text-indigo-600" id="total-display">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
                    
                    @if(isset($bankTransfer) && $bankTransfer)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Bank Transfer Details</h3>
                            @if(isset($bankDetails))
                                <div class="space-y-2 text-sm text-green-700">
                                    <p><strong>Bank Name:</strong> {{ $bankDetails['bank_name'] ?? 'N/A' }}</p>
                                    <p><strong>Account Name:</strong> {{ $bankDetails['account_name'] ?? 'N/A' }}</p>
                                    <p><strong>Account Number:</strong> {{ $bankDetails['account_number'] ?? 'N/A' }}</p>
                                    <p><strong>Reference:</strong> {{ $order->order_number ?? 'N/A' }}</p>
                                </div>
                            @endif
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">Please transfer the exact amount and use your order number as reference. Your order will be processed once payment is confirmed.</p>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('checkout.direct.process') }}" method="POST" id="checkout-form">
                            @csrf
                            @if(isset($book))
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            @endif
                            <input type="hidden" name="email" value="{{ auth()->user()->email ?? '' }}">
                            
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email" id="email" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter email address" value="{{ auth()->user()->email ?? '' }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name *</label>
                                <input type="text" name="customer_name" id="customer_name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter customer name" value="{{ auth()->user()->name ?? '' }}">
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="residence" class="block text-sm font-medium text-gray-700 mb-1">Residence *</label>
                                <input type="text" name="residence" id="residence" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter your residence address">
                                @error('residence')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality *</label>
                                <select name="nationality" id="nationality" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select your nationality</option>
                                    @forelse($nationalities as $nationality)
                                        <option value="{{ $nationality->name }}">{{ $nationality->name }}</option>
                                    @empty
                                        <option value="Ghanaian">Ghanaian</option>
                                        <option value="Nigerian">Nigerian</option>
                                        <option value="Kenyan">Kenyan</option>
                                        <option value="South African">South African</option>
                                        <option value="Togolese">Togolese</option>
                                        <option value="Ivorian">Ivorian</option>
                                        <option value="Burkinabe">Burkinabe</option>
                                        <option value="Liberian">Liberian</option>
                                        <option value="Sierra Leonean">Sierra Leonean</option>
                                        <option value="Cameroonian">Cameroonian</option>
                                        <option value="Other">Other</option>
                                    @endforelse
                                </select>
                                @error('nationality')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                             </div>
                            <div class="mb-6">
                                <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact Number *</label>
                                <input type="tel" name="contact" id="contact" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter contact number (e.g., 0551234567)">
                                @error('contact')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-all">
                                        <input type="radio" name="payment_method" value="card" class="sr-only" checked required>
                                        <div class="text-center payment-option" data-value="card">
                                            <img src="{{ asset('Stripe.jpg') }}" alt="Stripe" class="w-12 h-8 mx-auto mb-2 object-contain">
                                            <span class="font-medium text-gray-900">Credit/Debit Card</span>
                                            <p class="text-xs text-gray-500 mt-1">Pay via Stripe</p>
                                        </div>
                                    </label>
                                    <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all">
                                        <input type="radio" name="payment_method" value="paystack" class="sr-only" required>
                                        <div class="text-center payment-option" data-value="paystack">
                                            <img src="{{ asset('paystack.png') }}" alt="Paystack" class="w-12 h-8 mx-auto mb-2 object-contain">
                                            <span class="font-medium text-gray-900">Mobile Money</span>
                                            <p class="text-xs text-gray-500 mt-1">Secure payments</p>
                                        </div>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                              <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors" id="submit-btn">
                                  Confirm Order
                              </button>
                          </form>
                      @endif
            </div>
        </div>

        <x-customer-footer />

        <x-install-pwa />

        @include('feedback-widget')
        @include('components.cookie-consent')
    </div>

    <script>
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

        function updatePaymentSelection() {
            paymentOptions.forEach(option => {
                const label = option.closest('label');
                if (option.dataset.value === document.querySelector('input[name="payment_method"]:checked')?.value) {
                    if (option.dataset.value === 'paystack') {
                        label.classList.add('border-green-500', 'bg-green-50');
                        label.classList.remove('border-indigo-500', 'bg-indigo-50');
                    } else {
                        label.classList.add('border-indigo-500', 'bg-indigo-50');
                        label.classList.remove('border-green-500', 'bg-green-50');
                    }
                    label.classList.remove('border-gray-200');
                } else {
                    label.classList.remove('border-indigo-500', 'bg-indigo-50', 'border-green-500', 'bg-green-50');
                    label.classList.add('border-gray-200');
                }
            });
        }

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', updatePaymentSelection);
        });
        updatePaymentSelection();
    </script>
</body>
</html>
