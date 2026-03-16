<div id="pwa-install-container" class="fixed bottom-4 right-4 z-50">
    <button 
        id="pwa-install-btn"
        class="bg-indigo-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-3 hover:bg-indigo-700 transition-all transform hover:scale-105 font-semibold"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            console.log('beforeinstallprompt event fired');
        });

        // Handle successful installation
        window.addEventListener('appinstalled', function(e) {
            console.log('App installed successfully');
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
                
                if (deferredPrompt) {
                    // Show the native install prompt
                    deferredPrompt.prompt();
                    
                    const { outcome } = await deferredPrompt.userChoice;
                    
                    if (outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    
                    deferredPrompt = null;
                } else {
                    // No deferred prompt - try to trigger install via link click
                    // This works on some browsers
                    const link = document.createElement('a');
                    link.href = '/';
                    link.click();
                    
                    // Show message
                    alert('To install the app:\n\n1. Look for an install icon in your browser address bar\n2. Or go to your browser menu → Install App');
                }
            });
        }
    })();
</script>
