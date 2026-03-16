<div id="pwa-install-container" class="hidden fixed bottom-4 left-4 right-4 z-50 md:hidden">
    <button 
        id="pwa-install-btn"
        class="w-full bg-indigo-600 text-white py-4 px-6 rounded-xl shadow-lg flex items-center justify-center gap-3 hover:bg-indigo-700 transition-colors text-lg font-semibold"
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
               document.referrer.includes('android-app://');
    }

    // Show button on mobile (always show on mobile for visibility)
    function checkMobile() {
        if (window.innerWidth <= 768 && !isAppInstalled()) {
            installContainer.classList.remove('hidden');
            installContainer.classList.add('flex');
        } else {
            installContainer.classList.add('hidden');
            installContainer.classList.remove('flex');
        }
    }

    // Check on load and resize
    checkMobile();
    window.addEventListener('resize', checkMobile);

    // Listen for the beforeinstallprompt event (Chrome/Android)
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        checkMobile();
    });

    // Handle install button click
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
                installContainer.classList.add('hidden');
            } else {
                // For Safari or when prompt not available - show instructions
                const isAndroid = /Android/i.test(navigator.userAgent);
                const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
                
                let message = 'To install this app:\n\n';
                
                if (isAndroid) {
                    message += '• Tap the menu (three dots)\n• Tap "Add to Home Screen"';
                } else if (isIOS) {
                    message += '• Tap the Share button\n• Tap "Add to Home Screen"';
                } else {
                    message += '• Use Chrome on mobile to install';
                }
                
                alert(message);
            }
        });
    }

    // Handle successful install
    window.addEventListener('appinstalled', () => {
        installContainer.classList.add('hidden');
    });
</script>
