<x-layouts::auth.clean :title="__('Password Reset Verification')">
    <!-- Logo/Brand -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">BookShop</h1>
        <p class="text-gray-500 mt-1">Your Online Book Store</p>
    </div>

    <h2 class="text-xl font-bold text-gray-800 mb-1">Password Reset Verification</h2>
    <p class="text-gray-500 text-sm mb-5">Enter the 6-digit code sent to your email</p>

    <!-- Session Status -->
    @if (session('message'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="text-center mb-4">
        <p class="text-gray-600">
            We've sent a verification code to<br>
            <span class="font-semibold text-indigo-600">{{ $user->email }}</span>
        </p>
    </div>

    <form id="verification-form" method="POST" action="{{ route('verification.password-reset.verify') }}" class="space-y-4">
        @csrf

        <!-- Verification Code -->
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
            <input 
                type="text" 
                name="code" 
                id="code"
                maxlength="6" 
                required 
                autofocus
                placeholder="000000"
                class="w-full px-4 py-3 text-center text-2xl tracking-[0.5em] border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white text-gray-900"
            >
            @error('code')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button 
            type="submit" 
            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg"
        >
            Verify Code
        </button>
    </form>

    <!-- Resend Section -->
    <div class="mt-5 text-center">
        <p class="text-gray-600 text-sm mb-2">{{ __("Didn't receive the code?") }}</p>
        <form method="POST" action="{{ route('verification.password-reset.resend') }}" class="inline">
            @csrf
            <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                {{ __('Resend Code') }}
            </button>
        </form>
    </div>

    <!-- Back to Forgot Password -->
    <div class="mt-5 p-4 bg-gray-100 rounded-lg">
        <div class="flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="text-gray-600 text-sm">Back to</span>
            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                Forgot Password
            </a>
        </div>
    </div>

    <script>
        const form = document.getElementById('verification-form');
        const codeInput = document.querySelector('input[name="code"]');

        // Auto-format input to only numbers
        if (codeInput) {
            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Handle form submission with AJAX
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                
                fetch('{{ route('verification.password-reset.verify') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Code verified successfully!' && data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    </script>
</x-layouts::auth>
