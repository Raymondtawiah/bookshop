<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name', 'Visa Resources'))</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" href="/favicon.ico" sizes="any">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50 font-sans">
        <x-customer-navbar />
        <x-flash-message />

        <main class="pt-14">
            @yield('content')
        </main>
        <x-customer-footer />
    </body>
</html>
