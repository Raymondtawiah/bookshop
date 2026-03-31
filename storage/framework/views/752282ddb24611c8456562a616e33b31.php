<nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
<script>
// Handle back-forward cache (bfcache) - ensures fresh content on navigation
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page was loaded from bfcache - reload to get fresh content
        window.location.reload();
    }
});
</script>
<style>
@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
.animate-gradient {
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 300% 300%;
    animation: gradient-shift 3s ease infinite;
}
</style>
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-14">
            <!-- Logo -->
            <a href="<?php echo e(route('home')); ?>" class="font-bold text-indigo-600 text-lg">
                Nathaniel Gyarteng
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?php echo e(route('home')); ?>#home" class="text-gray-700 hover:text-indigo-600">Home</a>
                <!-- Always show Store link - it will scroll to store section -->
                <a href="<?php echo e(route('home')); ?>#store" class="text-gray-700 hover:text-indigo-600">Store</a>
                <a href="<?php echo e(route('visa-tip')); ?>" class="text-gray-700 hover:text-indigo-600">Visa Tips</a>
                <a href="<?php echo e(route('home')); ?>#about" class="text-gray-700 hover:text-indigo-600">About</a>
                <a href="<?php echo e(route('home')); ?>#contact" class="text-gray-700 hover:text-indigo-600">Contact</a>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <?php
                    $navbarCartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
                ?>
                <a href="<?php echo e(route('cart')); ?>" class="relative text-gray-700 hover:text-indigo-600 auth-only">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($navbarCartCount > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?php echo e($navbarCartCount); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
                <div class="relative group">
                    <button class="text-gray-700 hover:text-indigo-600 flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center animate-gradient">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block border">
                        <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                        <a href="<?php echo e(route('my-orders')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="text-gray-700 hover:text-indigo-600">Sign In</a>
                <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium">Register</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <?php echo $__env->make('components.customer-navbar-mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\enter\Herd\bookshop\resources\views/components/customer-navbar.blade.php ENDPATH**/ ?>