<!-- Hamburger Navigation -->
<div class="flex items-center gap-2 font-sans">
    <!-- Toggle Button -->
    <button id="mobile-menu-btn" class="p-2 text-gray-600 hover:text-indigo-600" onclick="toggleCustomerMobileMenu()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="menu-icon">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="close-icon">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

 <!-- Mobile Menu Dropdown -->
 <div id="customer-mobile-menu" class="absolute top-full left-0 right-0 bg-white shadow-lg border-t border-gray-200 p-4 z-[9999] overflow-visible" style="display: none; width: 100%; min-width: 100vw; opacity: 1 !important; visibility: visible !important;">
    <div class="flex flex-col space-y-3">
                      <a href="{{ route('home') }}#home" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">Home</a>
                      <a href="{{ route('visa-tip') }}" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">Visa Tips</a>
                      <a href="{{ route('webinars.index') }}" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">Webinars</a>
        <a href="{{ route('visa-training') }}" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">Visa Training</a>
          <a href="{{ route('home') }}#about" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">About</a>
          <a href="{{ route('home') }}#contact" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">Contact</a>
          <a href="{{ route('coaching.booking') }}" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Coaching</a>
        
        @auth
        <hr class="border-gray-200 my-2">
        <a href="{{ route('profile') }}" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">Settings</a>
        <a href="{{ route('my-orders') }}" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">My Orders</a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:text-red-800 font-medium transition-colors border-b border-gray-100">
                Logout
            </button>
        </form>
        @endauth
                     </div>
            
                     @guest
                     <hr class="border-gray-200 my-2">
         <a href="{{ route('login') }}" class="block py-2 px-3 text-gray-600 hover:text-indigo-600 font-medium transition-colors border-b border-gray-100">Sign In</a>
         @if (Route::has('register'))
         <a href="{{ route('register') }}" class="block py-2 px-3 text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Register</a>
         @endif
         @endguest
    </div>
</div>

<script>
    function toggleCustomerMobileMenu() {
        console.log('Toggle clicked');
        const mobileMenu = document.getElementById('customer-mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        console.log('Menu:', mobileMenu.style.display);
        
        // Force toggle display - add/remove a class instead of inline style
        if (mobileMenu.style.display === 'none') {
            mobileMenu.style.display = 'block';
            mobileMenu.classList.remove('hidden');
        } else {
            mobileMenu.style.display = 'none';
            mobileMenu.classList.add('hidden');
        }
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
</script>
