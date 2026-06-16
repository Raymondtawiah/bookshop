<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-8 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-3" wire:navigate>
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 shadow-xl shadow-indigo-600/30">
                        <x-app-logo-icon class="size-9 fill-current text-white" />
                    </span>
                    <span class="text-2xl font-bold text-neutral-800">{{ config('app.name', 'BookShop') }}</span>
                </a>

                <!-- Card -->
                <div class="rounded-2xl border border-neutral-200/60 bg-white shadow-2xl shadow-neutral-200/40">
                    <div class="px-8 py-10">{{ $slot }}</div>
                </div>

                <!-- Footer -->
                <p class="text-center text-sm text-neutral-400">
                    © {{ date('Y') }} {{ config('app.name', 'BookShop') }}. All rights reserved.
                </p>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
