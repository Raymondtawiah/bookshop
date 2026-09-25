<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>@yield('title', config('app.name', 'Visa Resources'))</title>
        
        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="BookShop">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="application-name" content="BookShop">
        <meta name="msapplication-TileColor" content="#4f46e5">
        
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" href="/favicon.ico" sizes="any">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link rel="stylesheet" href="{{ vite_asset('resources/css/app.css') }}">
        <script type="module" src="{{ vite_asset('resources/js/app.js') }}"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((registration) => {
                            console.log('SW registered: ', registration);
                        })
                        .catch((registrationError) => {
                            console.log('SW registration failed: ', registrationError);
                        });
                });
            }
        </script>
    </head>
    <body class="bg-gray-50 font-sans">
        <x-page-loader />
        <x-customer-navbar />
        <x-flash-message />

        <main class="pt-14">
            @yield('content')
        </main>
        <x-customer-footer />
        <x-free-book-modal />
        <x-install-pwa />
        @include('components.cookie-consent')
    </body>
</html>
