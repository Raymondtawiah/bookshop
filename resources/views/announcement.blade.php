@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Announcements</h1>
                    <p class="text-green-100 mt-2">Stay updated with the latest news and offers</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Limited Time Discounts!</h3>
                                <p class="text-gray-600 mt-2">Get 25% off all e-books and 30% off webinar registrations. Don't miss out on these exclusive offers!</p>
                                <a href="{{ route('discounts') }}" class="inline-block mt-4 px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-md">
                                    View Discounts
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="text-center text-gray-500 py-8">
                        <p>More announcements coming soon...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
