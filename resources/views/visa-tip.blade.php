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
        <section id="home" class="relative overflow-hidden bg-gray-100">
            <div style="width:100%;max-width:1200px;height:1000px;margin:0 auto;position:relative;">
                <img src="{{ asset('visa_tips.jpg') }}" alt="Hero" style="width:100%;height:100%;object-fit:cover;display:block;">
                <div id="star-overlay" style="position:absolute;inset:0;background:rgba(59, 130, 246, 0.2);"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-6 py-2 flex items-center justify-center">
            </div>
        </section>

        <style>
          @keyframes twinkle {
              0%, 100% { opacity: 0.3; transform: scale(1); }
              50% { opacity: 1; transform: scale(1.2); }
          }
          .star {
              position: absolute;
              background: white;
              border-radius: 50%;
              animation: twinkle 2s infinite ease-in-out;
          }
        </style>

        <script>
          (function() {
            const container = document.getElementById('star-overlay');
            if (!container) return;
            const count = 60;
            for (let i = 0; i < count; i++) {
                const star = document.createElement('div');
                star.style.position = 'absolute';
                star.style.top = Math.random() * 100 + '%';
                star.style.left = Math.random() * 100 + '%';
                const size = Math.random() * 3 + 1;
                star.style.width = size + 'px';
                star.style.height = size + 'px';
                star.style.background = 'white';
                star.style.borderRadius = '50%';
                star.style.opacity = Math.random() * 0.6 + 0.2;
                star.style.animation = 'twinkle ' + (Math.random() * 2 + 1) + 's infinite ease-in-out';
                star.style.animationDelay = Math.random() * 2 + 's';
                container.appendChild(star);
            }
          })();
        </script>

        <!-- Main Content -->
        <section id="content" class="py-16 bg-white">
            <div class="max-w-6xl mx-auto px-6">
                <!-- Introduction -->
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-sm font-bold mb-4">Essential Guidance</span>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Preparing for Your Visa Interview</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Practical guides to help students and travelers understand visa interviews, avoid common mistakes, and answer visa officer questions with confidence.</p>
                </div>

                <!-- Tips Grid -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Tip 1 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Be Clear About Your Study Plan</h3>
                                <p class="text-gray-600">Visa officers want to understand why you chose your program and school. Your explanation should be clear and connected to your career goals.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 2 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Know Your School and Program</h3>
                                <p class="text-gray-600">You should be able to explain:</p>
                                <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                                    <li>Why you chose that university</li>
                                    <li>What the program teaches</li>
                                    <li>How it helps your future career</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 3 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Be Honest and Confident</h3>
                                <p class="text-gray-600">Always answer questions truthfully and confidently. Visa officers are trained to notice when someone is unsure or inconsistent.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 4 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Understand Your Financial Support</h3>
                                <p class="text-gray-600">Be ready to clearly explain:</p>
                                <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                                    <li>Who is sponsoring you</li>
                                    <li>What they do for work</li>
                                    <li>How your education will be funded</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 5 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.06 0-2.077-.16-3.02-.454L3 21l1.5-4.5C3.55 15.16 3 13.63 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Keep Your Answers Short and Direct</h3>
                                <p class="text-gray-600">Visa interviews are usually very short. Avoid long explanations and answer the question directly.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 6 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Prepare for Common Questions</h3>
                                <p class="text-gray-600">Some common questions include:</p>
                                <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                                    <li>Why do you want to study in the U.S.?</li>
                                    <li>Why this school?</li>
                                    <li>What will you do after graduation?</li>
                                </ul>
                                <p class="text-gray-600 mt-2">Practicing your answers helps you stay confident.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 7 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Organize Your Documents</h3>
                                <p class="text-gray-600">Make sure your important documents are ready, such as:</p>
                                <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                                    <li>Passport</li>
                                    <li>I-20 form</li>
                                    <li>Financial documents</li>
                                    <li>Admission letter</li>
                                </ul>
                                <p class="text-gray-600 mt-2">Having them organized shows preparation.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 8 -->
                    <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Stay Calm During the Interview</h3>
                                <p class="text-gray-600">Even if the officer asks unexpected questions, stay calm and answer respectfully.</p>
                                <p class="text-gray-600 mt-2 font-medium">Confidence matters.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="mt-16 text-center">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-8 sm:p-12 text-white">
                        <h3 class="text-2xl sm:text-3xl font-extrabold mb-4">Ready to take the next step?</h3>
                        <p class="text-indigo-100 mb-8 max-w-2xl mx-auto">If you want personalized help, book a coaching session and walk into your visa interview with confidence.</p>
                        <a href="https://calendly.com/nathanielgyarteng/new-meeting-1?primary_color=6004d4" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-indigo-700 rounded-2xl font-bold text-base hover:bg-indigo-50 transition-all duration-200 shadow-xl">
                            Book a Coaching Session
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        </div>

      <x-customer-footer />
    </body>
</html>
