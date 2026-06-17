<x-layouts::auth.clean :title="__('Password Reset Verification')">
    <!-- Logo/Brand -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 mb-4">
            <img src="{{ asset('favicon.jpg') }}" alt="Logo" class="w-12 h-12 object-contain">
        </div>
        <h1 class="text-xl font-semibold text-gray-900">Visa Resources</h1>
        <p class="text-sm text-gray-500 mt-1">Practical guilds to help students</p>
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
            <span class="text-gray-600 text-sm">Back to</span>
            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                Forgot Password
            </a>
        </div>
    </div>

    <script>
        const form = document.getElementById('verification-form');
        const codeInput = document.querySelector('input[name="code"]');

        if (codeInput) {
            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

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
</x-layouts::auth.clean>
