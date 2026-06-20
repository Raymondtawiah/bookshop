<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-50">
        <x-flash-message />
        <x-customer-navbar />

            @yield('content')

        <x-customer-footer />

        <x-install-pwa />

        @fluxScripts
    </body>
</html>