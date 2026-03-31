<!-- Mobile Navigation (Phone View) -->
<div class="md:hidden flex items-center gap-2">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
    <?php
        $mobileCartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
    ?>
    <a href="<?php echo e(route('cart')); ?>" class="relative p-2 text-gray-600 hover:text-indigo-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mobileCartCount > 0): ?>
            <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?php echo e($mobileCartCount); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
<div id="customer-mobile-menu" class="hidden absolute top-full left-0 right-0 bg-white shadow-lg border-t border-gray-200 p-4" style="display: none;">
    <div class="flex flex-col space-y-3">
        <a href="<?php echo e(route('home')); ?>#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
        <a href="<?php echo e(route('home')); ?>#store" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Store</a>
        <a href="<?php echo e(route('visa-tip')); ?>" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Tips</a>
        <a href="<?php echo e(route('home')); ?>#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
        <a href="<?php echo e(route('home')); ?>#contact" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Contact</a>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <hr class="border-gray-200 my-2">
        
        <div class="flex items-center gap-3 py-2">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                <p class="text-xs text-gray-500"><?php echo e(auth()->user()->email); ?></p>
            </div>
        </div>
        <a href="<?php echo e(route('profile')); ?>" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Settings</a>
        <a href="<?php echo e(route('my-orders')); ?>" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">My Orders</a>
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-left text-red-600 hover:text-red-800 font-medium transition-colors w-full">
                Logout
            </button>
        </form>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
        <hr class="border-gray-200 my-2">
        <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Sign In</a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
        <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Register</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<script>
    function toggleCustomerMobileMenu() {
        const mobileMenu = document.getElementById('customer-mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        mobileMenu.classList.toggle('hidden');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.style.display = 'none';
        } else {
            mobileMenu.style.display = 'block';
        }
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
</script>
<?php /**PATH C:\Users\enter\Herd\bookshop\resources\views/components/customer-navbar-mobile.blade.php ENDPATH**/ ?>