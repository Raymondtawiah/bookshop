<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-neutral-100 antialiased">
        <!-- Background Decoration -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-purple-300 opacity-30 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-indigo-300 opacity-30 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-gradient-to-br from-indigo-200 to-purple-200 opacity-20 blur-3xl"></div>
        </div>

        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="mb-10 text-center">
                    <a href="{{ route('home') }}" class="inline-block">
                        <div class="flex items-center justify-center gap-3">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-600 to-fuchsia-600 shadow-2xl shadow-violet-600/30">
                                <x-app-logo-icon class="size-9 fill-current text-white" />
                            </div>
                        </div>
                    </a>
                    <h1 class="mt-6 text-4xl font-extrabold text-neutral-900 tracking-tight">{{ config('app.name', 'BookShop') }}</h1>
                    <p class="mt-2 text-base text-neutral-500">Your gateway to endless knowledge</p>
                </div>

                <!-- Card -->
                <div class="relative overflow-hidden rounded-3xl bg-white/80 backdrop-blur-xl shadow-2xl shadow-neutral-300/50 ring-1 ring-white/50">
                    <!-- Card Header Accent -->
                    <div class="h-1.5 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-violet-600"></div>
                    
                    <div class="px-8 py-10">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer -->
                <p class="mt-8 text-center text-sm text-neutral-400">
                    © {{ date('Y') }} {{ config('app.name', 'BookShop') }}. All rights reserved.
                </p>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
