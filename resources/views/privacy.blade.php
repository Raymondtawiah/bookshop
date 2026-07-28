@extends('layouts.app')

@section('title', 'Privacy Policy - Visa with Nathaniel')

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
                        <strong>Visa with Nathaniel</strong> exists to help people prepare for visa interviews and access webinars, resources, and coaching to improve their chances of approval.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Why We Collect Information</h2>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Create and manage user accounts for webinar registrations and coaching access</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Process payments securely for webinars and visa coaching services</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Provide access to webinars, study materials, and coaching sessions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Send webinar reminders and important updates about your visa preparation journey</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">How We Use Cookies</h2>
                    <p class="leading-relaxed mb-3">
                        Cookies help us remember your preferences, keep you logged in, and improve your experience across our visa preparation platform.
                    </p>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 font-bold">•</span>
                            <span><strong>Essential cookies:</strong> Required for login sessions, registrations, and secure payments</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 font-bold">•</span>
                            <span><strong>Preference cookies:</strong> Remember your progress through webinar materials and study resources</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 font-bold">•</span>
                            <span><strong>Analytics cookies:</strong> Help us understand how people use our platform to improve visa preparation content</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Your Data Safety</h2>
                    <p class="leading-relaxed">
                        All data is handled securely. Your personal information is saved only to provide our visa preparation services and is never sold to third parties. We use encryption and secure servers to protect your data.
                    </p>
                </div>

                <div class="bg-purple-50 rounded-xl p-6 border border-purple-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Your Rights</h2>
                    <p class="leading-relaxed">
                        You can request access to your data, update your information, or delete your account at any time by contacting us. You also have the right to opt out of non-essential cookies through your browser settings.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Contact Us</h2>
                    <p class="leading-relaxed mb-4">
                        If you have any questions about how we protect your data or how cookies are used on our platform, contact us at:
                    </p>
                    <div class="space-y-2">
                        <a href="mailto:nathanielgyarteng@gmail.com" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            nathanielgyarteng@gmail.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} Visa with Nathaniel. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
