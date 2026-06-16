@extends('layouts.auth.card')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Admin Login</h2>
    <p class="text-gray-600 mt-2">Sign in to access the admin dashboard</p>
</div>

<form method="POST" action="{{ route('admin.login.post') }}">
    @csrf

    <!-- Email Address -->
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address
        </label>
        <input 
            id="email" 
            name="email" 
            type="email" 
            value="{{ old('email') }}"
            required 
            autofocus
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
            placeholder="admin@example.com"
        >
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
        </label>
        <input 
            id="password" 
            name="password" 
            type="password" 
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
            placeholder="••••••••"
        >
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between mb-6">
        <label class="flex items-center">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
        Sign In
    </button>
</form>

<div class="mt-6 text-center">
    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700">
        Back to Customer Login
    </a>
</div>
@endsection
