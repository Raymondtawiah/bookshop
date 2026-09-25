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

        (function() {
            const INACTIVITY_LIMIT = 30 * 60 * 1000;
            const WARNING_LIMIT = 29 * 60 * 1000;
            const LOGOUT_URL = '{{ route('admin.logout') }}';
            const CSRF_TOKEN = '{{ csrf_token() }}';

            let timeoutId = null;
            let warned = false;

            function logout() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = LOGOUT_URL;
                form.style.display = 'none';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = CSRF_TOKEN;
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();
            }

            function showWarning() {
                warned = true;
                const message = document.createElement('div');
                Object.assign(message.style, {
                    position: 'fixed',
                    top: '20px',
                    right: '20px',
                    background: '#fff',
                    border: '1px solid #e5e7eb',
                    borderRadius: '12px',
                    padding: '16px 20px',
                    boxShadow: '0 10px 25px rgba(0,0,0,0.15)',
                    zIndex: '9999',
                    fontFamily: 'system-ui, -apple-system, sans-serif',
                    minWidth: '260px'
                });
                message.innerHTML = `
                    <div style="font-weight:600;color:#111827;margin-bottom:6px;">Session expiring soon</div>
                    <div style="font-size:13px;color:#374151;margin-bottom:10px;">You will be logged out in 1 minute due to inactivity.</div>
                    <button id="pwa-keep-login" style="background:#4f46e5;color:#fff;border:none;padding:8px 12px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">Stay logged in</button>
                `;
                document.body.appendChild(message);

                document.getElementById('pwa-keep-login').addEventListener('click', function() {
                    message.remove();
                    warned = false;
                    resetInactivityTimer();
                });
            }

            function resetInactivityTimer() {
                if (timeoutId) clearTimeout(timeoutId);
                warned = false;

                timeoutId = setTimeout(function() {
                    if (!warned) {
                        showWarning();
                        setTimeout(function() {
                            if (!warned) return;
                            logout();
                        }, 60 * 1000);
                    }
                }, INACTIVITY_LIMIT);
            }

            const activityEvents = ['mousemove', 'click', 'keydown', 'scroll', 'touchstart', 'mousedown'];
            activityEvents.forEach(function(eventName) {
                document.addEventListener(eventName, function() {
                    resetInactivityTimer();
                });
            });

            resetInactivityTimer();
        })();
    </script>

        @stack('scripts')
    </body>
</html>
