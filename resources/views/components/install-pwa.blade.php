<div id="pwa-install-container" class="hidden fixed bottom-4 left-4 z-50">
    <button 
        id="pwa-install-btn"
        class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 hover:bg-indigo-700 transition-colors"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    // Show button on mobile
    function checkMobile() {
        if (window.innerWidth <= 768 && !isAppInstalled()) {
            installContainer.classList.remove('hidden');
        } else {
            installContainer.classList.add('hidden');
        }
    }

    // Check on load and resize
    checkMobile();
    window.addEventListener('resize', checkMobile);

    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installContainer.classList.remove('hidden');
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
                // For Safari - guide users to manually install
                alert('To install this app on iPhone:\n\n1. Tap the Share button (square with arrow)\n2. Tap "Add to Home Screen"');
            }
        });
    }

    // Handle successful install
    window.addEventListener('appinstalled', () => {
        installContainer.classList.add('hidden');
    });
</script>
