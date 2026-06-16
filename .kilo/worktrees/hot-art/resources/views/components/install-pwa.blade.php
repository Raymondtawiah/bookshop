<script>
    (function() {
        if (window.matchMedia('(display-mode: standalone)').matches ||
            window.matchMedia('display-mode: fullscreen').matches ||
            window.matchMedia('display-mode: minimal-ui').matches ||
            window.navigator.standalone === true) {
            return;
        }

        window.addEventListener('beforeinstallprompt', function(e) {
            console.log('[PWA] Install prompt available — browser will show it naturally');
        });
    })();
</script>
