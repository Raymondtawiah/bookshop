<script>
    (function() {
        if (window.matchMedia('(display-mode: standalone)').matches ||
            window.matchMedia('(display-mode: fullscreen)').matches ||
            window.matchMedia('(display-mode: minimal-ui)').matches ||
            window.navigator.standalone === true) {
            return;
        }

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            e.prompt();
        });
    })();
</script>
<?php /**PATH C:\Users\enter\Herd\bookshop\resources\views/components/install-pwa.blade.php ENDPATH**/ ?>