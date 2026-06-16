<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-neutral-50 antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <!-- Left Side - Visual Panel -->
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-200">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800"></div>
                <!-- Decorative Pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");"></div>
                
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-white/20 backdrop-blur-sm">
                        <x-app-logo-icon class="me-2 h-7 fill-current text-white" />
                    </span>
                    <span class="text-white/90">{{ config('app.name', 'BookShop') }}</span>
                </a>

                <!-- Hero Content -->
                <div class="relative z-20 mt-auto flex flex-col items-center justify-center text-center">
                    <div class="mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                        <svg class="h-12 w-12 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <flux:heading size="3xl" class="mb-4 text-white">Welcome to BookShop</flux:heading>
                    <p class="max-w-md text-lg text-white/80">Your gateway to a world of knowledge. Discover, learn, and grow with our vast collection of books.</p>
                    
                    <!-- Feature Pills -->
                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <span class="rounded-full bg-white/20 px-4 py-1.5 text-sm text-white backdrop-blur-sm">📚 Thousands of Books</span>
                        <span class="rounded-full bg-white/20 px-4 py-1.5 text-sm text-white backdrop-blur-sm">📖 Read Anywhere</span>
                        <span class="rounded-full bg-white/20 px-4 py-1.5 text-sm text-white backdrop-blur-sm">⚡ Instant Access</span>
                    </div>
                </div>
                
                <!-- Bottom Quote -->
                <div class="relative z-20 mt-8">
                    <blockquote class="space-y-2 border-l-2 border-white/30 pl-4">
                        <flux:heading size="base" class="italic text-white/80">&ldquo;A reader lives a thousand lives before he dies.&rdquo;</flux:heading>
                        <footer class="text-white/60">— George R.R. Martin</footer>
                    </blockquote>
                </div>
            </div>
            
            <!-- Right Side - Form -->
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <!-- Mobile Logo -->
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600">
                            <x-app-logo-icon class="size-7 fill-current text-white" />
                        </span>
                        <span class="text-xl font-semibold">{{ config('app.name', 'BookShop') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
