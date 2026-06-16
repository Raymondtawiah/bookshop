<footer class="bg-white text-gray-900 py-6 mt-auto border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <p class="text-sm">&copy; {{ date('Y') }} Visa With Nathaniel. All rights reserved.</p>

            </div>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}#about" class="hover:text-blue-600 transition-colors">About</a>
                <a href="{{ route('home') }}#contact" class="hover:text-blue-600 transition-colors">Contact</a>
                <a href="{{ route('visa-tip') }}" class="hover:text-blue-600 transition-colors">Visa Tips</a>
                {{-- <a href="{{ route('visa-training') }}" class="hover:text-blue-600 transition-colors">Visa Training</a> --}}
            </div>
        </div>
    </div>
</footer>
