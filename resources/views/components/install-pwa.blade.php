<div id="pwa-install-container" class="hidden fixed bottom-4 right-4 z-50">
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

        // Show the install button
        function showInstallButton() {
            if (installContainer && !isAppInstalled()) {
                installContainer.classList.remove('hidden');
                installContainer.classList.add('block');
            }
        }

        // Hide the install button
        function hideInstallButton() {
            if (installContainer) {
                installContainer.classList.add('hidden');
                installContainer.classList.remove('block');
            }
        }

        // Store the deferred prompt when received
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            console.log('beforeinstallprompt event fired');
            showInstallButton();
        });

        // Handle successful installation
        window.addEventListener('appinstalled', function(e) {
            console.log('App installed successfully');
            hideInstallButton();
            deferredPrompt = null;
        });

        // Check on page load if already installed or if we should show the button
        document.addEventListener('DOMContentLoaded', function() {
            // Check if the browser supports PWA install
            if (!('serviceWorker' in navigator)) {
                return;
            }
            
            // Show button after a short delay if deferredPrompt exists
            setTimeout(function() {
                if (deferredPrompt) {
                    showInstallButton();
                }
            }, 1500);
        });

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
                        hideInstallButton();
                    }
                    
                    deferredPrompt = null;
                } else {
                    // No deferred prompt - show manual instructions
                    const userAgent = navigator.userAgent || '';
                    let instructions = '';
                    
                    if (userAgent.includes('Android') || userAgent.includes('Chrome')) {
                        instructions = 'To install the app:\n\n1. Tap the menu (three dots) in the top right corner\n2. Tap "Install App" or "Add to Home Screen"';
                    } else if (userAgent.includes('iPhone') || userAgent.includes('iPad')) {
                        instructions = 'To install on iPhone/iPad:\n\n1. Tap the Share button 📤 (bottom center)\n2. Scroll down and tap "Add to Home Screen"\n3. Tap "Add" in the top right';
                    } else {
                        instructions = 'To install the app:\n\n• Look for an install icon in the address bar\n• Or right-click and select "Save as Web App"';
                    }
                    
                    alert('📱 INSTALL APP\n\n' + instructions);
                }
            });
        }
    })();
</script>
