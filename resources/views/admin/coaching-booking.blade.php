<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Personal Coaching - {{ config('app.name', 'Nathaniel Gyarteng') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/favicon.ico" sizes="any">
    <style>
        html, body {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        .plan-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans pt-16 m-0 p-0 box-border w-full min-w-0">
    <x-page-loader />
    <x-flash-message />
    
    @include('components.customer-navbar')
    
    @php
        $isActive = \App\Models\SiteSetting::get('coaching_booking_active', 'true') === 'true';
        $isAdmin = auth()->check() && auth()->user()->is_admin;
    @endphp

    @if(!$isActive && !$isAdmin)
    <div id="disabled-message" class="min-h-[70vh] flex items-center justify-center px-4">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-10 sm:p-14 max-w-lg text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Booking Currently Unavailable</h2>
            <p class="text-gray-600 text-lg leading-relaxed">We're sorry, but the coaching booking service is temporarily paused.</p>
            <p class="text-gray-500 mt-4">Please check back later or reach out for assistance.</p>
        </div>
    </div>
    @endif

    @if($isActive || $isAdmin)
    <section class="py-16 sm:py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="text-xs font-bold tracking-widest uppercase text-indigo-700 mb-3">Personal Coaching</p>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">Practice your visa interview with Nathaniel</h1>
                <h2 class="text-lg sm:text-xl text-gray-600 font-medium mb-4">One focused session with feedback on your answers</h2>
                <p class="text-sm text-gray-500">30 minutes . online . $50 USD</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-6 sm:p-10">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Choose appointment</h3>

                <form method="POST" action="{{ route('coaching.store') }}" class="space-y-6" id="bookingForm">
                    @csrf
                    <input type="hidden" name="package" value="single">
                    <input type="hidden" name="interview_type" value="Visa Interview">

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="interview_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="interview_date" id="interview_date" value="{{ old('interview_date') }}" required min="{{ date('Y-m-d', strtotime('next saturday')) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                            <p id="date-error" class="hidden text-xs text-red-600 font-medium mt-1.5">Please select a Saturday or Sunday only.</p>
                        </div>

                        <div>
                            <label for="interview_time" class="block text-sm font-semibold text-gray-700 mb-1.5">Time <span class="text-red-500">*</span></label>
                            <select name="interview_time" id="interview_time" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all appearance-none cursor-pointer">
                                <option value="">Select time...</option>
                                <option value="10:00" {{ old('interview_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                <option value="13:00" {{ old('interview_time') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                <option value="16:00" {{ old('interview_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Jane Smith" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-gray-400">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="you@example.com" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-gray-400">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+233 555 123 456" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-gray-400" required>
                    </div>

                    <div>
                        <label for="mobile_network" class="block text-sm font-semibold text-gray-700 mb-1.5">Mobile Network</label>
                        <select name="mobile_network" id="mobile_network" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all appearance-none cursor-pointer">
                            <option value="">Select network...</option>
                            <option value="mtn" {{ old('mobile_network') == 'mtn' ? 'selected' : '' }}>MTN</option>
                            <option value="vodafone" {{ old('mobile_network') == 'vodafone' ? 'selected' : '' }}>Vodafone</option>
                            <option value="airteltigo" {{ old('mobile_network') == 'airteltigo' ? 'selected' : '' }}>AirtelTigo</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Optional. Defaults to MTN if left blank.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                        <div class="grid sm:grid-cols-2 gap-3" id="payment-methods">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="stripe" required class="sr-only" {{ old('payment_method') == 'stripe' ? 'checked' : '' }}>
                                <div class="payment-option rounded-2xl border-2 border-gray-200 bg-white transition-all p-4 text-center">
                                    <div class="text-sm font-bold text-gray-900">Stripe</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="momo" required class="sr-only" {{ old('payment_method') == 'momo' ? 'checked' : '' }}>
                                <div class="payment-option rounded-2xl border-2 border-gray-200 bg-white transition-all p-4 text-center">
                                    <div class="text-sm font-bold text-gray-900">Momo</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full plan-gradient text-white py-4 rounded-3xl font-bold text-lg hover:opacity-90 transition-all duration-200 shadow-lg shadow-indigo-200 flex items-center justify-center gap-3">
                        <span>Confirm Booking</span>
                        <span class="font-extrabold">$50</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>
    @endif
    @include('chat-widget')
    @include('components.customer-footer')

    <script>
        function checkBookingStatus() {
            fetch('{{ route("coaching.status") }}')
                .then(response => response.json())
                .then(data => {
                    const disabledMessage = document.getElementById('disabled-message');
                    if (data.is_active === false) {
                        if (disabledMessage && disabledMessage.classList.contains('hidden')) {
                            disabledMessage.classList.remove('hidden');
                        }
                    } else {
                        if (disabledMessage && !disabledMessage.classList.contains('hidden')) {
                            disabledMessage.classList.add('hidden');
                        }
                    }
                })
                .catch(error => console.error('Error checking booking status:', error));
        }

        checkBookingStatus();
        setInterval(checkBookingStatus, 10000);
    </script>

    <script>
        (function() {
            const dateInput = document.getElementById('interview_date');
            const dateError = document.getElementById('date-error');
            const bookingForm = document.getElementById('bookingForm');

            function isWeekend(value) {
                if (!value) return false;
                const date = new Date(value + 'T00:00:00');
                const day = date.getDay();
                return day === 0 || day === 6;
            }

            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    if (!isWeekend(this.value)) {
                        dateError.classList.remove('hidden');
                        this.setAttribute('aria-invalid', 'true');
                    } else {
                        dateError.classList.add('hidden');
                        this.removeAttribute('aria-invalid');
                    }
                });
            }

            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    if (!isWeekend(dateInput.value)) {
                        e.preventDefault();
                        dateError.classList.remove('hidden');
                        dateInput.setAttribute('aria-invalid', 'true');
                        dateInput.focus();
                    }
                });
            }
        })();
    </script>

    <script>
        (function() {
            const container = document.getElementById('payment-methods');
            if (!container) return;

            function updatePaymentHighlight() {
                const selected = container.querySelector('input[name="payment_method"]:checked');
                container.querySelectorAll('.payment-option').forEach(function(el) {
                    if (selected && el.previousElementSibling && el.previousElementSibling.checked) {
                        el.classList.add('border-indigo-500', 'bg-indigo-50', 'shadow-md', 'shadow-indigo-200');
                        el.classList.remove('border-gray-200', 'bg-white');
                        el.querySelector('div').classList.add('text-indigo-700');
                        el.querySelector('div').classList.remove('text-gray-900');
                    } else {
                        el.classList.remove('border-indigo-500', 'bg-indigo-50', 'shadow-md', 'shadow-indigo-200');
                        el.classList.add('border-gray-200', 'bg-white');
                        el.querySelector('div').classList.remove('text-indigo-700');
                        el.querySelector('div').classList.add('text-gray-900');
                    }
                });
            }

            container.querySelectorAll('input[name="payment_method"]').forEach(function(input) {
                input.addEventListener('change', updatePaymentHighlight);
            });

            updatePaymentHighlight();
        })();
    </script>
</body>
</html>
