<div id="pwa-install-container" class="hidden fixed bottom-0 left-0 right-0 z-50 md:hidden">
    <button 
        id="pwa-install-btn"
        class="w-full bg-indigo-600 text-white py-4 px-6 rounded-t-xl shadow-lg flex items-center justify-center gap-3 hover:bg-indigo-700 transition-colors text-lg font-semibold"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        <span>Install App to Home Screen</span>
    </button>
</div>

<script>
    let deferredPrompt;
    const installContainer = document.getElementById('pwa-install-container');
    const installBtn = document.getElementById('pwa-install-btn');

    // Check if app is already installed
    function isAppInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches || 
               window.navigator.standalone === true;
    }

    // Show button on mobile
    function checkMobile() {
        if (window.innerWidth <= 768 && !isAppInstalled()) {
            installContainer.classList.remove('hidden');
            installContainer.classList.add('block');
        } else {
            installContainer.classList.add('hidden');
            installContainer.classList.remove('block');
        }
    }

    // Check on load and resize
    checkMobile();
    window.addEventListener('resize', checkMobile);

    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        checkMobile();
    });

    // Handle install button click
    if (installBtn) {
        installBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
            } else {
                // Manual install instructions
                const userAgent = navigator.userAgent || '';
                let instructions = '';
                
                if (userAgent.includes('Android')) {
                    instructions = 'On Android Chrome:\n\n1. Tap the menu (three dots) 📋\n2. Tap "Add to Home Screen" ➕\n\nOR\n\n1. Tap the Share button 📤\n2. Tap "Add to Home Screen" ➕';
                } else if (userAgent.includes('iPhone') || userAgent.includes('iPad')) {
                    instructions = 'On iPhone/iPad (Safari):\n\n1. Tap the Share button 📤\n2. Scroll down and tap "Add to Home Screen" ➕';
                } else {
                    instructions = 'To install:\n\n1. Open the website in Chrome on your phone\n2. Tap menu → Add to Home Screen';
                }
                
                alert(' INSTALL APP\n\n' + instructions);
            }
        });
    }

    // Handle successful install
    window.addEventListener('appinstalled', () => {
        installContainer.classList.add('hidden');
        alert('🎉 App installed successfully!');
    });
</script>
