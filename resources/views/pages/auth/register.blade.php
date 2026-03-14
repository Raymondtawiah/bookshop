<x-layouts::auth.clean :title="__('Register')">
    <!-- Logo/Brand -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">BookShop</h1>
        <p class="text-gray-500 mt-1">Your Online Book Store</p>
    </div>

    <h2 class="text-xl font-bold text-gray-800 mb-1">Create Account</h2>
    <p class="text-gray-500 text-sm mb-5">Join us and start your reading journey</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input 
                type="text" 
                name="name" 
                id="name"
                value="{{ old('name') }}"
                required 
                autofocus
                autocomplete="name"
                placeholder="John Doe"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white text-gray-900"
            >
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input 
                type="email" 
                name="email" 
                id="email"
                value="{{ old('email') }}"
                required 
                autocomplete="email"
                placeholder="you@example.com"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white text-gray-900"
            >
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input 
                type="password" 
                name="password" 
                id="password"
                required 
                autocomplete="new-password"
                placeholder="Create a password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white text-gray-900"
            >
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation"
                required 
                autocomplete="new-password"
                placeholder="Confirm your password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white text-gray-900"
            >
        </div>

        <!-- Submit -->
        <button 
            type="submit" 
            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg"
        >
            Create Account
        </button>
    </form>

    <p class="mt-5 text-center text-gray-500">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
            Sign in
        </a>
    </p>
</x-layouts::auth.clean>
