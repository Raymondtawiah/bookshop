<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Admin') - {{ config('app.name', 'Bookshop') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ vite_asset('resources/css/app.css') }}">
        <script type="module" src="{{ vite_asset('resources/js/app.js') }}"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-white font-sans" style="margin: 0; padding: 0;">
        <x-flash-message />
        <x-page-loader />

        <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <div class="app">
            <x-admin-sidebar />

            <main class="main">
                @yield('content')
            </main>
        </div>

        <script>
            function toggleFilters(id) {
                const body = document.getElementById(id);
                if (!body) return;
                if (body.style.display === 'none') {
                    body.style.display = '';
                } else {
                    body.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (window.innerWidth <= 900) {
                    const statsBody = document.getElementById('stats-body');
                    const webinarBody = document.getElementById('webinar-filter-body');
                    const coachingBody = document.getElementById('coaching-filter-body');
                    const webinarStats = document.getElementById('webinar-stats-body');
                    const coachingStats = document.getElementById('coaching-stats-body');

                    if (statsBody) statsBody.style.display = 'none';
                    if (webinarBody) webinarBody.style.display = 'none';
                    if (coachingBody) coachingBody.style.display = 'none';
                    if (webinarStats) webinarStats.style.display = 'none';
                    if (coachingStats) coachingStats.style.display = 'none';
                }
            });

            (function() {
                const sidebar = document.getElementById('admin-sidebar');
                const toggle = document.getElementById('sidebar-toggle');
                const overlay = document.getElementById('sidebar-overlay');

                if (!sidebar || !toggle || !overlay) {
                    return;
                }

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

        @stack('scripts')
    </body>
</html>
