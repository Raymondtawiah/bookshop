<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Finance') - {{ config('app.name', 'Bookshop') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
        @stack('scripts')
    </head>
    <body class="bg-gray-50 font-sans">
        <x-flash-message />

        <header class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold">Finance Dashboard</h1>
                        <p class="text-emerald-100 mt-1">Track financial activities and manage records</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('finance.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm hover:underline">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>

        <x-admin-footer />
    </body>
</html>