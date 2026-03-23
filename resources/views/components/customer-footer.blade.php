<footer class="bg-gray-800 text-gray-300 py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <p class="text-sm">&copy; {{ date('Y') }} Nathaniel Gyarteng. All rights reserved.</p>
                @auth
                <p class="text-xs text-gray-400 mt-1">Licensed to: {{ auth()->user()->name }}</p>
                @endauth
            </div>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}#about" class="hover:text-white transition-colors">About</a>
                <a href="{{ route('home') }}#contact" class="hover:text-white transition-colors">Contact</a>
                <a href="{{ route('visa-tip') }}" class="hover:text-white transition-colors">Visa Tips</a>
            </div>
        </div>
    </div>
</footer>
