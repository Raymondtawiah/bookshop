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
    let deferredPrompt;
    const installContainer = document.getElementById('pwa-install-container');
    const installBtn = document.getElementById('pwa-install-btn');

    // Check if app is already installed
    function isAppInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches || 
               window.navigator.standalone === true ||
               window.matchMedia('(display-mode: fullscreen)').matches;
    }

    // Show button if not installed and beforeinstallprompt is available
    function showInstallButton() {
        if (!isAppInstalled() && installContainer) {
            installContainer.classList.remove('hidden');
            installContainer.classList.add('block');
        }
    }

    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        showInstallButton();
    });

    // Also check on load in case the event fired before we listened
    window.addEventListener('load', () => {
        // Give a moment for the event to potentially fire
        setTimeout(() => {
            if (!deferredPrompt && !isAppInstalled()) {
                // Event might have fired before we added the listener
                // Try to show button anyway
                showInstallButton();
            }
        }, 2000);
    });

    // Handle install button click
    if (installBtn) {
        installBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
                
                if (outcome === 'accepted') {
                    installContainer.classList.add('hidden');
                    installContainer.classList.remove('block');
                }
            } else {
                // Manual install - show instructions based on user agent
                const userAgent = navigator.userAgent || '';
                let instructions = '';
                
                if (userAgent.includes('Android') || userAgent.includes('Chrome')) {
                    instructions = 'To install the app:\n\n1. Tap the menu (three dots) in the top right corner\n2. Tap "Install App" or "Add to Home Screen"\n\nOR\n\n1. Tap the Share button 📤\n2. Tap "Add to Home Screen" ➕';
                } else if (userAgent.includes('iPhone') || userAgent.includes('iPad') || userAgent.includes('Safari')) {
                    instructions = 'To install on iPhone/iPad:\n\n1. Tap the Share button 📤 (bottom center)\n2. Scroll down and tap "Add to Home Screen" ➕\n3. Tap "Add" in the top right';
                } else {
                    instructions = 'To install the app:\n\n• On Desktop: Look for an install icon in the address bar\n• On Mobile: Tap menu → Add to Home Screen';
                }
                
                alert('📱 INSTALL APP\n\n' + instructions);
            }
        });
    }

    // Handle successful install
    window.addEventListener('appinstalled', () => {
        if (installContainer) {
            installContainer.classList.add('hidden');
            installContainer.classList.remove('block');
        }
    });
</script>
