<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Finance') - {{ config('app.name', 'Bookshop') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" href="/favicon.ico" sizes="any">
        <style>
            .finance-sidebar {
                transform: translateX(-100%);
                transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 40;
            }
            .finance-sidebar.is-open {
                transform: translateX(0);
            }
            .finance-sidebar-edge {
                position: fixed;
                top: 0;
                left: 0;
                width: 22px;
                height: 100vh;
                z-index: 39;
                opacity: 0;
                transition: opacity 0.35s ease;
                pointer-events: auto;
                background: linear-gradient(to right, rgba(0,0,0,0.08), transparent);
            }
            .finance-sidebar-edge.is-visible {
                opacity: 1;
            }
            .finance-toggle {
                position: fixed;
                top: 16px;
                left: 14px;
                z-index: 41;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                border: none;
                background: #ffffff;
                color: #4f46e5;
                box-shadow: 0 4px 12px rgba(0,0,0,0.12);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 1;
                transform: scale(1);
                transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease, transform 0.35s ease;
                pointer-events: auto;
            }
            .finance-toggle.is-open {
                left: 266px;
            }
        </style>
    </head>
    <body class="bg-gray-50 font-sans">
        <div class="finance-sidebar-wrapper relative flex min-h-screen">
            <div class="finance-sidebar-edge" id="financeSidebarEdge"></div>

            <!-- Sidebar -->
            <aside class="finance-sidebar fixed inset-y-0 left-0 w-64 bg-white shadow-2xl" id="financeSidebar">
                <div class="flex items-center gap-3 px-6 h-16 border-b border-gray-100">
                    <img src="{{ asset('icon.jpg') }}" alt="Visa with Nathaniel" class="w-9 h-9 rounded-lg shadow object-cover">
                    <span class="font-bold text-lg text-gray-900">Finance Panel</span>
                </div>
                <nav class="px-4 py-4 space-y-1">
                    <a href="{{ route('finance.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('finance.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m7-7l7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('finance.income') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('finance.income*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Income
                    </a>
                    <a href="{{ route('finance.expenses') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('finance.expenses*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2-4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/>
                        </svg>
                        Expenses
                    </a>
                    <a href="{{ route('finance.payments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('finance.payments') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Payments
                    </a>
                    <a href="{{ route('finance.reports') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('finance.reports') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Reports
                    </a>
                    <a href="{{ route('finance.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('finance.settings') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </nav>
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-indigo-600">
                            <img src="{{ asset('user_icon.jpg') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('finance.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <button class="finance-toggle is-visible" id="financeToggle" title="Toggle menu">
                <svg id="financeToggleIcon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12h18M3 6h18M3 18h18"/>
                </svg>
            </button>

            <!-- Main Content -->
            <div class="flex-1 w-full">
                <main class="p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            (function() {
                const sidebar = document.getElementById('financeSidebar');
                const edge = document.getElementById('financeSidebarEdge');
                const toggle = document.getElementById('financeToggle');
                const toggleIcon = document.getElementById('financeToggleIcon');
                if (!sidebar || !edge || !toggle || !toggleIcon) return;

                const hamburgerSvg = '<path d="M3 12h18M3 6h18M3 18h18"/>';
                const closeSvg = '<path d="M18 6L6 18M6 6l12 12"/>';

                function openSidebar() {
                    sidebar.classList.add('is-open');
                    edge.classList.remove('is-visible');
                    toggle.classList.add('is-open');
                    toggleIcon.innerHTML = closeSvg;
                }

                function closeSidebar() {
                    sidebar.classList.remove('is-open');
                    edge.classList.add('is-visible');
                    toggle.classList.remove('is-open');
                    toggleIcon.innerHTML = hamburgerSvg;
                }

                edge.addEventListener('mouseenter', function() {
                    openSidebar();
                });

                sidebar.addEventListener('mouseleave', function() {
                    closeSidebar();
                });

                toggle.addEventListener('click', function() {
                    if (sidebar.classList.contains('is-open')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            })();
        </script>

        <script>
            (function() {
                const IDLE_TIMEOUT = 120;
                const LOGOUT_URL = '{{ route('finance.logout') }}';
                const CSRF_TOKEN = '{{ csrf_token() }}';
                let idleTimer = null;

                function resetIdleTimer() {
                    clearTimeout(idleTimer);
                    idleTimer = setTimeout(autoLogout, IDLE_TIMEOUT * 1000);
                }

                function autoLogout() {
                    navigator.sendBeacon(LOGOUT_URL, new URLSearchParams({ _token: CSRF_TOKEN }));
                    setTimeout(function() {
                        window.location.href = '{{ route('finance.login') }}';
                    }, 200);
                }

                const events = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
                events.forEach(function(evt) {
                    document.addEventListener(evt, resetIdleTimer, true);
                });

                idleTimer = setTimeout(autoLogout, IDLE_TIMEOUT * 1000);
            })();
        </script>

        @stack('scripts')
    </body>
</html>
