@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
                <div class="w-20 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>

            <!-- Content -->
            <div class="space-y-8 text-gray-700">
                <div>
                    <p class="text-lg leading-relaxed">
                        We value your privacy. This app collects basic user information such as name, email address, and phone number to provide our services.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">We use this information to:</h2>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Create and manage user accounts</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Process payments securely</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Provide access to webinars and services</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Data Sharing</h2>
                    <p class="leading-relaxed">
                        We do not sell or share your personal information with third parties except as required to provide our services (e.g., payment processing).
                    </p>
                </div>

                <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Data Security</h2>
                    <p class="leading-relaxed">
                        All data is handled securely.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Contact Us</h2>
                    <p class="leading-relaxed mb-4">
                        If you have any questions, contact us at:
                    </p>
                    <div class="space-y-2">
                        <a href="mailto:realgalaxyfc8@gmail.com" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            realgalaxyfc8@gmail.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} {{ config('app.name', 'Bookshop') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
