<x-layouts::auth.clean :title="__('Forgot Password')">
    <!-- Logo/Brand -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 mb-4">
            <img src="{{ asset('favicon.jpg') }}" alt="Logo" class="w-12 h-12 object-contain">
        </div>
        <h1 class="text-xl font-semibold text-gray-900">Visa Resources</h1>
        <p class="text-sm text-gray-500 mt-1">Practical guilds to help students</p>
    </div>

    <h2 class="text-xl font-bold text-gray-800 mb-1">Forgot Password?</h2>
    <p class="text-gray-500 text-sm mb-5">Enter your email and we'll send you a verification code</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
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

    <form method="POST" action="{{ route('verification.password-reset.send') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input
                type="email"
                name="email"
                id="email"
                required
                autofocus
                placeholder="you@example.com"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white text-gray-900"
            >
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg"
        >
            Send Verification Code
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-5 p-4 bg-gray-100 rounded-lg">
        <div class="flex items-center justify-center gap-2">
            <span class="text-gray-600 text-sm">Remember your password?</span>
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                Sign in
            </a>
        </div>
    </div>
</x-layouts::auth.clean>
