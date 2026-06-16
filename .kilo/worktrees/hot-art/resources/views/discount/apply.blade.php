@extends('layouts.app')

@section('title', 'Apply Discount - Bookshop')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Apply Discount Code</h1>
        <p class="text-gray-600">Enter your discount code to get savings on your e-book purchase</p>
    </div>

    <!-- Discount Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('discount.apply') }}" id="discount-form">
            @csrf
            
            <div class="mb-6">
                <label for="discount_code" class="block text-sm font-medium text-gray-700 mb-2">Discount Code</label>
                <input type="text" 
                    name="discount_code" 
                    id="discount_code" 
                    placeholder="Enter code (e.g., helloWorld25)" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg"
                    value="{{ old('discount_code') }}"
                    required>
                @error('discount_code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if(session('discount_applied'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-green-700 font-medium">Discount applied successfully!</span>
                </div>
                <p class="text-sm text-green-600 mt-1">Your discount has been saved. You will see the reduced price at checkout.</p>
            </div>
            @elseif(session('discount_error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-red-700 font-medium">{{ session('discount_error') }}</span>
                </div>
            </div>
            @endif

            <button type="submit" 
                class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all">
                Apply Discount
            </button>
        </form>

        <!-- Available Discounts Info -->
        <div class="mt-8 pt-6 border-t">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Available Discounts:</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                    EARLYBIRDS25 - 25% off all e-books
                </li>
            </ul>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('checkout') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Skip discount and go to checkout →
            </a>
        </div>
    </div>
</div>
@endsection