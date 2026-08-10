@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Back link -->
        <a href="{{ route('webinars.show', $webinar) }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 mb-8">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Webinar
        </a>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-8">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Webinar Summary -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Registration</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Webinar</span>
                            <span class="font-medium text-gray-900">{{ $webinar->title }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Attendee Name</span>
                            <span class="font-medium text-gray-900">{{ $registration->full_name }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Email</span>
                            <span class="font-medium text-gray-900">{{ $registration->email }}</span>
                        </div>
                        @if($registration->phone)
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Phone</span>
                                <span class="font-medium text-gray-900">{{ $registration->phone }}</span>
                            </div>
                        @endif
                    </div>

                    @if($webinar->scheduled_at)
                        <div class="bg-indigo-50 rounded-xl p-4 mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                     <p class="text-sm font-medium text-indigo-900">{{ $webinar->scheduled_at->timezone('Africa/Accra')->format('F j, Y') }}</p>
                                     <p class="text-sm text-indigo-700">{{ $webinar->scheduled_at->timezone('Africa/Accra')->format('g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Amount -->
                    <div class="bg-gray-50 rounded-xl p-6 text-center mb-6">
                        <p class="text-sm text-gray-500 mb-2">Amount to Pay</p>
                         <p class="text-4xl font-bold text-indigo-600 mb-2">${{ number_format($webinar->current_price, 2) }}</p>
                         <p class="text-sm text-gray-500 mt-2">Pay via Stripe</p>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Payment Method</h2>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">Select your preferred payment method</p>

                        <div class="space-y-3 mb-6">
                            <label class="flex items-center gap-3 border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-indigo-300 transition-colors">
                                <input type="radio" name="provider" value="stripe" checked class="h-4 w-4 text-indigo-600">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Pay with Card (Stripe)</p>
                                    <p class="text-xs text-gray-500">${{ number_format($webinar->current_price, 2) }} USD</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-indigo-300 transition-colors">
                                <input type="radio" name="provider" value="paystack" class="h-4 w-4 text-indigo-600">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Pay with Momo</p>
                                    <p class="text-xs text-gray-500">GHS {{ number_format($webinar->current_price * 11.65, 2) }} (approx)</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="button" onclick="initiatePayment()" class="w-full py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium text-lg flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Proceed to Payment
                    </button>

                    <div class="mt-6 text-center">
                         <p class="text-sm text-gray-500">
                             Your payment is secured by
                             <span class="font-semibold text-indigo-600">Stripe And Paystack</span>
                         </p>
                    </div>
                </div>

                <div class="mt-6 bg-gray-50 rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <p class="text-sm text-gray-600 mb-4">If you encounter any issues during payment, please contact our support team.</p>
                    <a href="mailto:nathanielgyarteng@gmail.com" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                        Contact Support →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for submitting payment -->
    <form id="paymentForm" action="{{ route('webinars.payment.initiate', [$webinar, $registration]) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="provider" id="providerInput" value="stripe">
    </form>

    <script>
        function initiatePayment() {
            const provider = document.querySelector('input[name="provider"]:checked')?.value || 'stripe';
            document.getElementById('providerInput').value = provider;

            const button = document.querySelector('button[onclick="initiatePayment()"]');
            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Processing...';

            const formData = new FormData(document.getElementById('paymentForm'));

            fetch('{{ route("webinars.payment.initiate", [$webinar, $registration]) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.url) {
                    window.location.href = data.url;
                } else {
                    alert(data.message || 'Payment initialization failed. Please try again.');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                alert('An error occurred while processing your payment. Please try again.');
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }
    </script>
@endsection
