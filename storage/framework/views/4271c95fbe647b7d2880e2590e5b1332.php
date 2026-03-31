<!-- Scroll Animation Script - Include once in the layout -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only run if IntersectionObserver is supported
    if (!('IntersectionObserver' in window)) {
        // Fallback: show all elements immediately
        document.querySelectorAll('[data-animate-target]').forEach(el => {
            el.classList.remove('opacity-0', 'translate-y-8', 'translate-x-[-50px]', 'translate-x-50');
        });
        return;
    }
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const targets = entry.target.querySelectorAll('[data-animate-target]');
                targets.forEach((el, index) => {
                    // Get the delay from the element or use default
                    const delay = parseInt(el.dataset.animateDelay) || (index * 100);
                    setTimeout(() => {
                        el.classList.remove('opacity-0', 'translate-y-8', 'translate-x-[-50px]', 'translate-x-50');
                    }, delay);
                });
                observer.unobserve(entry.target);
            }
        });
    }, { 
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observe all animated sections
    document.querySelectorAll('[data-animate]').forEach(section => {
        observer.observe(section);
    });
});
</script>
<?php /**PATH C:\Users\enter\Herd\bookshop\resources\views/components/sections/animation-script.blade.php ENDPATH**/ ?>