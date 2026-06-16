<div id="pwa-install-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <button 
        id="pwa-install-btn"
        style="background-color: #4f46e5; color: white; padding: 12px 24px; border-radius: 9999px; display: flex; align-items: center; gap: 8px; font-weight: 600; border: none; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.3);"
    >
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        <span>Install App</span>
    </button>
</div>

<script>
    (function() {
        let deferredPrompt;
        const installContainer = document.getElementById('pwa-install-container');
        const installBtn = document.getElementById('pwa-install-btn');

        console.log('PWA Install: Button loaded');

        // Check if app is already installed
        function isAppInstalled() {
            return window.matchMedia('(display-mode: standalone)').matches || 
                   window.matchMedia('(display-mode: fullscreen)').matches ||
                   window.matchMedia('(display-mode: minimal-ui)').matches ||
                   window.navigator.standalone === true;
        }

        // Hide the install button if app is already installed
        function checkAndHideIfInstalled() {
            if (isAppInstalled() && installContainer) {
                installContainer.style.display = 'none';
            }
        }

        // Store the deferred prompt when received
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            console.log('PWA Install: beforeinstallprompt fired');
        });

        // Handle successful installation
        window.addEventListener('appinstalled', function(e) {
            console.log('PWA Install: App installed');
            if (installContainer) {
                installContainer.style.display = 'none';
            }
            deferredPrompt = null;
        });

        // Check on page load if already installed
        checkAndHideIfInstalled();

        // Handle install button click
        if (installBtn) {
            installBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                console.log('PWA Install: Button clicked, deferredPrompt:', deferredPrompt);
                
                if (deferredPrompt) {
                    // Show the native install prompt
                    deferredPrompt.prompt();
                    
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log('PWA Install: User choice:', outcome);
                    
                    deferredPrompt = null;
                } else {
                    // No deferred prompt - show message
                    alert('To install this app:\n\n1. On mobile: Tap menu → Add to Home Screen\n2. On desktop: Look for install icon in address bar');
                }
            });
        }
    })();
</script>
