<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Checkout - Bookshop</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 antialiased">
        <x-flash-message />
        <x-customer-navbar />

        <!-- Main Content -->
        <!-- Main Content -->
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
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
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
        <div class="max-w-3xl mx-auto px-6 pt-24 pb-12">
            @if(isset($order) && $order)
                <!-- Payment Pending / Bank Transfer / Order Success -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    @if(isset($paymentPending) && $paymentPending)
                        <!-- Mobile Money Payment Pending -->
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Pending!</h1>
                            <p class="text-gray-600">{{ $paymentMessage ?? 'Please approve the payment on your mobile phone.' }}</p>
                        </div>
                    @elseif(isset($bankTransfer) && $bankTransfer)
                        <!-- Bank Transfer Details -->
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Bank Transfer Details</h1>
                            <p class="text-gray-600 mb-6">Please make your payment to the account below and keep the receipt.</p>
                            
                            <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto">
                                <div class="space-y-3 text-left">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Bank Name:</span>
                                        <span class="font-bold text-gray-900">{{ $bankDetails['bank_name'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Account Name:</span>
                                        <span class="font-bold text-gray-900">{{ $bankDetails['account_name'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Account Number:</span>
                                        <span class="font-bold text-gray-900">{{ $bankDetails['account_number'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Branch:</span>
                                        <span class="font-bold text-gray-900">{{ $bankDetails['branch'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="border-t pt-3 mt-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 font-semibold">Amount:</span>
                                            <span class="font-bold text-indigo-600 text-lg">₵{{ number_format($total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500 mt-4">Your order will be processed once payment is confirmed.</p>
                        </div>
                    @else
                        <!-- Order Success -->
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
                            <p class="text-gray-600">Thank you for your purchase. Your order has been placed.</p>
                            @if(isset($order->order_number))
                            <p class="text-sm text-gray-500 mt-2">Order Reference: <span class="font-mono font-bold">{{ $order->order_number }}</span></p>
                            @endif
                        </div>
                    @endif

                    <div class="border-t pt-6">
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Total Amount Paid:</span>
                            <span class="font-bold text-indigo-600">₵{{ number_format($total, 2) }}</span>
                        </div>
                        @if($order->customer_name)
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Customer Name:</span>
                            <span class="font-bold text-gray-900">{{ $order->customer_name }}</span>
                        </div>
                        @endif
                        @if($order->email)
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Email:</span>
                            <span class="font-bold text-gray-900">{{ $order->email }}</span>
                        </div>
                        @endif
                        @if($order->contact)
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Contact:</span>
                            <span class="font-bold text-gray-900">{{ $order->contact }}</span>
                        </div>
                        @endif
                        @if($order->residence)
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Residence:</span>
                            <span class="font-bold text-gray-900">{{ $order->residence }}</span>
                        </div>
                        @endif
                        @if($order->payment_method)
                        <div class="flex justify-between text-lg mb-2">
                            <span class="font-semibold">Payment Method:</span>
                            <span class="font-bold text-gray-900">{{ $order->payment_method === 'momo' ? 'Mobile Money' : 'Bank Transfer' }}</span>
                        </div>
                        @endif
                    </div>

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
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf
                        
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
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter email address" value="{{ auth()->user()->email ?? '' }}">
                            @error('email')
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
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-all">
                                    <input type="radio" name="payment_method" value="momo" class="sr-only" required checked>
                                    <div class="text-center payment-option" data-value="momo">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-medium text-gray-900">Mobile Money</span>
                                        <p class="text-xs text-gray-500 mt-1">Pay via MoMo</p>
                                    </div>
                                </label>
                                <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-all">
                                    <input type="radio" name="payment_method" value="bank" class="sr-only peer">
                                    <div class="text-center payment-option" data-value="bank">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span class="font-medium text-gray-900">Bank Transfer</span>
                                        <p class="text-xs text-gray-500 mt-1">Pay via Bank</p>
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
                    
                    <script>
                        function detectNetwork(phoneNumber) {
                            const phone = phoneNumber.replace(/[^0-9]/g, '');
                            let prefix;
                            if (phone.length === 12 && phone.substring(0, 3) === '233') {
                                prefix = phone.substring(3, 6);
                            } else if (phone.length === 10 && phone.substring(0, 1) === '0') {
                                prefix = phone.substring(1, 4);
                            } else {
                                prefix = phone.substring(0, 3);
                            }
                            
                            if (['540', '550', '560', '570', '580', '240', '250'].includes(prefix)) return 'MTN';
                            if (['520', '530', '540', '200', '210'].includes(prefix)) return 'VODAFONE';
                            if (['270', '280', '290', '571'].includes(prefix)) return 'AIRTELTIGO';
                            return 'MTN';
                        }

                        // Handle payment method selection highlighting
                        const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
                        paymentOptions.forEach(option => {
                            option.addEventListener('change', function() {
                                // Remove highlight from all labels
                                document.querySelectorAll('.grid-cols-2 > label').forEach(label => {
                                    label.classList.remove('border-indigo-500', 'bg-indigo-50');
                                    label.classList.add('border-gray-200');
                                });
                                // Add highlight to selected label
                                if (this.checked) {
                                    this.closest('label').classList.add('border-indigo-500', 'bg-indigo-50');
                                    this.closest('label').classList.remove('border-gray-200');
                                }
                            });
                            // Trigger initial state
                            if (option.checked) {
                                option.closest('label').classList.add('border-indigo-500', 'bg-indigo-50');
                                option.closest('label').classList.remove('border-gray-200');
                            }
                        });

                        document.getElementById('checkout-form').addEventListener('submit', async function(e) {
                            e.preventDefault();
                            
                            const submitBtn = document.getElementById('submit-btn');
                            const originalText = submitBtn.innerText;
                            submitBtn.disabled = true;
                            submitBtn.innerText = 'Processing...';
                            
                            const formData = new FormData(this);
                            const paymentMethod = formData.get('payment_method');
                            const contact = formData.get('contact');
                            
                            const data = {
                                customer_name: formData.get('customer_name'),
                                email: formData.get('email'),
                                residence: formData.get('residence'),
                                contact: contact,
                                payment_method: paymentMethod,
                                mobile_number: contact,
                                network: detectNetwork(contact),
                                _token: '{{ csrf_token() }}'
                            };
                            
                            try {
                                const response = await fetch('{{ route("payment.initialize") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify(data)
                                });
                                
                                const result = await response.json();
                                
                                if (result.success) {
                                    if (result.authorization_url) {
                                        // Redirect to Paystack
                                        window.location.href = result.authorization_url;
                                    } else if (result.message) {
                                        // Mobile money - show message
                                        alert(result.message);
                                        // Reload to show order success
                                        window.location.reload();
                                    }
                                } else {
                                    alert(result.message || 'Payment failed. Please try again.');
                                    submitBtn.disabled = false;
                                    submitBtn.innerText = originalText;
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                alert('An error occurred. Please try again.');
                                submitBtn.disabled = false;
                                submitBtn.innerText = originalText;
                            }
                        });
                    </script>
                </div>
            @endif
        </div>
    </body>
</html>
