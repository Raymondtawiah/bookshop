<script>
    (function() {
        if (window.matchMedia('(display-mode: standalone)').matches ||
            window.matchMedia('(display-mode: fullscreen)').matches ||
            window.matchMedia('(display-mode: minimal-ui)').matches ||
            window.navigator.standalone === true) {
            return;
        }

        var deferredPrompt;

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;

            function promptOnInteraction() {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(function() {
                        deferredPrompt = null;
                    });
                }
                document.removeEventListener('click', promptOnInteraction);
            }

            document.addEventListener('click', promptOnInteraction);
        });
    })();
</script>
