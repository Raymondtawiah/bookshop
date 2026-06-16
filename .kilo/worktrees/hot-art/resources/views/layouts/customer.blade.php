<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-50">
        <x-flash-message />
        <x-customer-navbar />

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 md:px-6 pt-24 pb-12">
            @yield('content')
        </div>

        <x-customer-footer />

        <x-install-pwa />

        @fluxScripts
    </body>
</html>