<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Finance') - {{ config('app.name', 'Bookshop') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ vite_asset('resources/css/app.css') }}">
        <script type="module" src="{{ vite_asset('resources/js/app.js') }}"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 font-sans">
        <div class="app">
            <aside class="sidebar" id="finance-sidebar">
                <div class="logo">
                    <div class="logo-main">FINANCE</div>
                    <div class="logo-sub">Panel</div>
                </div>
                <nav class="nav">
                    <a href="{{ route('finance.dashboard') }}" class="nav-item {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 12l2-2m7-7l7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </span>
                        Dashboard
                    </a>
                    <a href="{{ route('finance.attendance') }}" class="nav-item {{ request()->routeIs('finance.attendance') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Attendance
                    </a>
                    <a href="{{ route('finance.income') }}" class="nav-item {{ request()->routeIs('finance.income*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Income
                    </a>
                    <a href="{{ route('finance.expenses') }}" class="nav-item {{ request()->routeIs('finance.expenses*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2-4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/>
                            </svg>
                        </span>
                        Expenses
                    </a>
                    <a href="{{ route('finance.payments') }}" class="nav-item {{ request()->routeIs('finance.payments') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </span>
                        Payments
                    </a>
                    <a href="{{ route('finance.reports') }}" class="nav-item {{ request()->routeIs('finance.reports') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        Reports
                    </a>
                    <a href="{{ route('finance.settings') }}" class="nav-item {{ request()->routeIs('finance.settings') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        Settings
                    </a>
                </nav>
                <form method="POST" action="{{ route('finance.logout') }}" class="mt-auto" onsubmit="return confirm('Are you sure you want to logout?')">
                    @csrf
                    <button type="submit" class="nav-item" style="border: none; background: none; cursor: pointer; width: 100%;">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </span>
                        Logout
                    </button>
                </form>
            </aside>

            <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle sidebar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="sidebar-overlay" id="sidebar-overlay"></div>

            <main class="main">
                @yield('content')
            </main>
        </div>

        <script>
            (function() {
                const sidebar = document.getElementById('finance-sidebar');
                const toggle = document.getElementById('sidebar-toggle');
                const overlay = document.getElementById('sidebar-overlay');

                if (!sidebar || !toggle || !overlay) return;

                function openSidebar() {
                    sidebar.classList.add('open');
                    overlay.classList.add('open');
                }

                function closeSidebar() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                }

                toggle.addEventListener('click', function() {
                    if (sidebar.classList.contains('open')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });

                overlay.addEventListener('click', closeSidebar);
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
