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
                                    <p class="text-sm font-medium text-indigo-900">{{ $webinar->scheduled_at->format('F j, Y') }}</p>
                                    <p class="text-sm text-indigo-700">{{ $webinar->scheduled_at->format('g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Amount -->
                    <div class="bg-gray-50 rounded-xl p-6 text-center mb-6">
                        <p class="text-sm text-gray-500 mb-2">Amount to Pay</p>
                        <p class="text-4xl font-bold text-indigo-600 mb-2">₵{{ number_format($webinar->price, 2) }}</p>
                        <p class="text-sm text-gray-500">Pay via Paystack</p>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Payment Method</h2>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">We accept secure payments via Paystack</p>
                        
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-indigo-300 transition-colors">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                </svg>
                                <p class="text-sm font-medium">Card</p>
                            </div>
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-indigo-300 transition-colors">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Bank</p>
                            </div>
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-indigo-300 transition-colors">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Mobile</p>
                            </div>
                        </div>
                    </div>

                    <button onclick="initiatePayment()" class="w-full py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium text-lg flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Pay Now with Paystack
                    </button>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500">
                            Your payment is secured by
                            <span class="font-semibold text-indigo-600">Paystack</span>
                        </p>
                    </div>
                </div>

                <div class="mt-6 bg-gray-50 rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <p class="text-sm text-gray-600 mb-4">If you encounter any issues during payment, please contact our support team.</p>
                    <a href="mailto:support@bookshop.com" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                        Contact Support →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for submitting payment -->
    <form id="paymentForm" action="{{ route('webinars.payment.initiate', [$webinar, $registration]) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function initiatePayment() {
            const button = document.querySelector('button[onclick="initiatePayment()"]');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Processing...';
            
            // Get form data
            const formData = new FormData(document.getElementById('paymentForm'));
            
            // Make AJAX request
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
                if (data.success && data.authorization_url) {
                    // Redirect to Paystack
                    window.location.href = data.authorization_url;
                } else {
                    // Show error message
                    alert(data.message || 'Payment initialization failed. Please try again.');
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                alert('An error occurred while processing your payment. Please try again.');
                // Reset button
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }
    </script>
@endsection
