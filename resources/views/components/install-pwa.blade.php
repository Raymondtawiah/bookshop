s<div id="pwa-install-container" class="hidden fixed bottom-4 left-4 z-50">
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

    // Hide by default, show on mobile only if not installed
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
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later
        deferredPrompt = e;
        // Show our custom install button on mobile
        checkMobile();
    });

    // Handle install button click
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                // Show the prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                // Clear the deferredPrompt
                deferredPrompt = null;
                // Hide the button
                installContainer.classList.add('hidden');
            }
        });
    }

    // Handle successful install
    window.addEventListener('appinstalled', () => {
        // Hide the install button after successful install
        installContainer.classList.add('hidden');
        console.log('PWA installed successfully');
    });
</script>
