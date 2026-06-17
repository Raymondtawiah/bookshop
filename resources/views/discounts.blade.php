@extends('layouts.app')

@section('title', 'Special Discounts - Bookshop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Special Limited-Time Discounts</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Take advantage of our exclusive discounts on e-books and webinars. Limited time only!</p>
    </div>

    <!-- Discount Cards -->
    <div class="grid md:grid-cols-2 gap-8 mb-12">
        <!-- E-book Discount -->
        <div class="relative overflow-hidden bg-white rounded-3xl shadow-xl border border-gray-100 group hover:shadow-2xl transition-all">
            <div class="absolute top-0 right-0 bg-green-500 text-white font-bold px-6 py-2 rounded-bl-xl text-2xl">25% OFF</div>
            <div class="p-8">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-200 transition-colors">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">E-book Purchases</h2>
                <p class="text-gray-600 mb-6">Get 25% off all digital books in our store. Access instant downloadable content on visa preparation, interview strategies, and more.</p>
                
                <div class="bg-green-50 rounded-xl p-4 mb-6">
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Instant digital download after purchase
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Comprehensive visa interview guides
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Expert-curated content for success
                        </li>
                    </ul>
                </div>

                <a href="{{ route('home') }}#store" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all">
                    Browse E-books Now
                </a>
            </div>
            <div class="h-2 bg-gradient-to-r from-green-500 to-emerald-500"></div>
        </div>

        <!-- Webinar Discount -->
        <div class="relative overflow-hidden bg-white rounded-3xl shadow-xl border border-gray-100 group hover:shadow-2xl transition-all">
            <div class="absolute top-0 right-0 bg-purple-500 text-white font-bold px-6 py-2 rounded-bl-xl text-2xl">30% OFF</div>
            <div class="p-8">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Webinar Registration</h2>
                <p class="text-gray-600 mb-6">Save 30% on all webinar registrations. Join live interactive sessions with visa experts and get your questions answered in real-time.</p>
                
                <div class="bg-purple-50 rounded-xl p-4 mb-6">
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Live Q&A with immigration experts
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Interactive case studies and examples
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Downloadable resources after each session
                        </li>
                    </ul>
                </div>

                <a href="{{ route('webinars.index') }}" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all">
                    View Webinars
                </a>
            </div>
            <div class="h-2 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 mb-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">How to Get Your Discount</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                    <span class="text-2xl font-bold text-indigo-600">1</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Browse Products</h3>
                <p class="text-gray-600 text-sm">Explore our e-books or webinars section to find what you need.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                    <span class="text-2xl font-bold text-indigo-600">2</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Add to Cart</h3>
                <p class="text-gray-600 text-sm">Select your items and proceed to checkout.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                    <span class="text-2xl font-bold text-indigo-600">3</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Discount Applied</h3>
                <p class="text-gray-600 text-sm">Your discount is automatically applied at checkout.</p>
            </div>
        </div>
    </div>

<!-- Back to Dashboard -->
     <div class="text-center">
         <a href="{{ route('discount.apply.form') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium mr-4">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
             </svg>
             Apply Discount Code
         </a>
         <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
             </svg>
             Back to Dashboard
         </a>
     </div>
</div>
@endsection