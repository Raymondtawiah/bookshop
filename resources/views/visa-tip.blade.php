<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Visa Tips - {{ config('app.name', 'Bookshop') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" href="/favicon.ico" sizes="any">
        <style>
            html, body {
                max-width: 100% !important;
                overflow-x: hidden !important;
            }
            #app, body > div:first-child {
                max-width: 100% !important;
                overflow-x: hidden !important;
            }
        </style>
    </head>
    <body class="antialiased m-0 p-0 box-border w-full min-w-0">
        <x-flash-message />
        <x-customer-navbar />
        
        <div class="w-full overflow-x-hidden min-w-0">
        
        <!-- Hero Section -->
        <section class="py-20 text-white max-w-full relative overflow-hidden min-h-screen flex items-center" 
                 style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.85) 50%, rgba(139, 92, 246, 0.85) 100%), url('{{ asset('visa.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
            
            <!-- Animated overlay pattern -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.3"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
            
            <!-- Floating elements animation -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
                <div class="absolute top-1/4 right-20 w-32 h-32 bg-blue-400/20 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
                <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-purple-400/20 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 right-1/3 w-24 h-24 bg-indigo-400/20 rounded-full animate-bounce" style="animation-delay: 1.5s;"></div>
            </div>
            
            <div class="px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid md:grid-cols-2 gap-12 items-center max-w-7xl mx-auto">
                    <div class="space-y-8">
                        <!-- Badge with glow effect -->
                        <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full border border-white/30 shadow-lg">
                            <span class="relative flex h-3 w-3 mr-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            <span class="text-sm font-medium">Expert Advice • Proven Strategies</span>
                        </div>
                        
                        <!-- Main heading with solid text for better visibility -->
                        <h1 class="text-5xl md:text-6xl font-bold leading-tight text-white">
                            Visa Interview
                            <br>
                            Tips
                        </h1>
                        
                        <!-- Description with better typography -->
                        <p class="text-xl md:text-2xl text-blue-100 leading-relaxed max-w-lg">
                            Master your visa interview with expert guidance. Learn proven strategies, avoid common mistakes, and answer confidently to get your visa approved.
                        </p>
                        
                        <!-- CTA button with enhanced styling -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#content" class="px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                                Get Started
                            </a>
                        </div>
                        
                        <!-- Stats with enhanced cards -->
                        <div class="grid grid-cols-3 gap-6">
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                                <div class="text-3xl font-bold text-white">
                                    8+
                                </div>
                                <div class="text-white text-sm font-medium">Essential Tips</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                                <div class="text-3xl font-bold text-white">
                                    100%
                                </div>
                                <div class="text-white text-sm font-medium">Free Guide</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                                <div class="text-3xl font-bold text-white">
                                    Pro
                                </div>
                                <div class="text-white text-sm font-medium">Expert Advice</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced image section with effects -->
                    <div class="hidden md:block relative">
                        <div class="relative group">
                            <!-- Glow effect behind image -->
                            <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                            
                            <!-- Main image with enhanced styling -->
                            <img src="{{ asset('visa.jpg') }}" alt="Visa Interview Tips" 
                                 class="relative rounded-3xl shadow-2xl w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Floating badge -->
                            <div class="absolute -top-4 -right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg animate-bounce">
                                FREE GUIDE
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section id="content" class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-6">
                <!-- Introduction -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Preparing for Your Visa Interview</h2>
                    <p class="text-xl text-gray-600">Practical guides to help students and travelers understand visa interviews, avoid common mistakes, and answer visa officer questions with confidence.</p>
                </div>

                <!-- Tips Grid -->
                <div class="space-y-8">
                    <!-- Tip 1 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                1
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Be Clear About Your Study Plan</h3>
                                <p class="text-gray-700">Visa officers want to understand why you chose your program and school. Your explanation should be clear and connected to your career goals.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 2 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                2
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Know Your School and Program</h3>
                                <p class="text-gray-700">You should be able to explain:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Why you chose that university</li>
                                    <li>What the program teaches</li>
                                    <li>How it helps your future career</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 3 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                3
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Be Honest and Confident</h3>
                                <p class="text-gray-700">Always answer questions truthfully and confidently. Visa officers are trained to notice when someone is unsure or inconsistent.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 4 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                4
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Understand Your Financial Support</h3>
                                <p class="text-gray-700">Be ready to clearly explain:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Who is sponsoring you</li>
                                    <li>What they do for work</li>
                                    <li>How your education will be funded</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 5 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                5
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Keep Your Answers Short and Direct</h3>
                                <p class="text-gray-700">Visa interviews are usually very short. Avoid long explanations and answer the question directly.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 6 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                6
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Prepare for Common Questions</h3>
                                <p class="text-gray-700">Some common questions include:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Why do you want to study in the U.S.?</li>
                                    <li>Why this school?</li>
                                    <li>What will you do after graduation?</li>
                                </ul>
                                <p class="text-gray-700 mt-2">Practicing your answers helps you stay confident.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 7 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                7
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Organize Your Documents</h3>
                                <p class="text-gray-700">Make sure your important documents are ready, such as:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Passport</li>
                                    <li>I-20 form</li>
                                    <li>Financial documents</li>
                                    <li>Admission letter</li>
                                </ul>
                                <p class="text-gray-700 mt-2">Having them organized shows preparation.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 8 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                8
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Stay Calm During the Interview</h3>
                                <p class="text-gray-700">Even if the officer asks unexpected questions, stay calm and answer respectfully.</p>
                                <p class="text-gray-700 mt-2 font-medium">Confidence matters.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center mt-16">
                    <p class="text-gray-600 mb-6">Want more detailed guidance? Check out our books for comprehensive visa preparation.</p>
                    <a href="{{ route('home') }}#store" class="inline-block px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity">
                        Browse Our Books
                    </a>
                </div>
            </div>
        </section>
        </div>

      <x-customer-footer />
    </body>
</html>
