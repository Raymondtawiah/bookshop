<script>
    (function() {
        if (window.matchMedia('(display-mode: standalone)').matches ||
            window.matchMedia('display-mode: fullscreen').matches ||
            window.matchMedia('display-mode: minimal-ui').matches ||
            window.navigator.standalone === true) {
            return;
        }

        const INSTALLED_KEY = 'pwa_installed';

        if (localStorage.getItem(INSTALLED_KEY)) return;

        let deferredPrompt = null;

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            console.log('[PWA] Install prompt captured');

            setTimeout(showInstallModal, 800);
        });

        function showInstallModal() {
            if (document.getElementById('pwa-install-modal')) return;

            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);

            const overlay = document.createElement('div');
            overlay.id = 'pwa-install-overlay';
            Object.assign(overlay.style, {
                position: 'fixed',
                top: '0',
                left: '0',
                width: '100%',
                height: '100%',
                background: 'rgba(0,0,0,0.6)',
                zIndex: '99999',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontFamily: 'system-ui, -apple-system, sans-serif',
                padding: '20px',
                backdropFilter: 'blur(4px)'
            });

            const modal = document.createElement('div');
            Object.assign(modal.style, {
                background: '#fff',
                borderRadius: '24px',
                padding: '36px 28px',
                maxWidth: '400px',
                width: '100%',
                textAlign: 'center',
                boxShadow: '0 25px 60px rgba(0,0,0,0.4)',
                animation: 'modalPop 0.3s ease-out'
            });

            const style = document.createElement('style');
            style.textContent = '@keyframes modalPop{from{transform:scale(0.9);opacity:0}to{transform:scale(1);opacity:1}}';
            modal.appendChild(style);

            const icon = document.createElement('div');
            Object.assign(icon.style, {
                width: '72px',
                height: '72px',
                background: 'linear-gradient(135deg, #4f46e5, #7c3aed)',
                borderRadius: '18px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: '36px',
                margin: '0 auto 20px'
            });
            icon.textContent = '📚';

            const title = document.createElement('h2');
            title.textContent = 'Install Our App';
            Object.assign(title.style, {
                fontSize: '22px',
                fontWeight: '700',
                color: '#1f2937',
                marginBottom: '8px'
            });

            const desc = document.createElement('p');
            desc.textContent = 'Get the best experience with our app — quick access, offline reading, and instant notifications.';
            Object.assign(desc.style, {
                fontSize: '14px',
                color: '#6b7280',
                lineHeight: '1.6',
                marginBottom: '28px'
            });

            const installBtn = document.createElement('button');
            installBtn.textContent = isIOS ? 'How to Install' : 'Install Now';
            Object.assign(installBtn.style, {
                background: 'linear-gradient(135deg, #4f46e5, #7c3aed)',
                color: '#fff',
                border: 'none',
                padding: '14px 28px',
                borderRadius: '14px',
                fontWeight: '600',
                cursor: 'pointer',
                fontSize: '15px',
                width: '100%',
                marginBottom: '10px',
                boxShadow: '0 4px 14px rgba(79, 70, 229, 0.4)'
            });

            const iosBtn = document.createElement('button');
            iosBtn.textContent = 'iOS: Share → Add to Home Screen';
            Object.assign(iosBtn.style, {
                background: '#f3f4f6',
                color: '#374151',
                border: 'none',
                padding: '12px 18px',
                borderRadius: '12px',
                fontWeight: '500',
                cursor: 'pointer',
                fontSize: '13px',
                width: '100%',
                display: isIOS ? 'block' : 'none'
            });

            installBtn.addEventListener('click', function() {
                if (isIOS) {
                    installBtn.style.display = 'none';
                    iosBtn.style.display = 'block';
                } else if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(function(choiceResult) {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('[PWA] User accepted install');
                            localStorage.setItem(INSTALLED_KEY, '1');
                            overlay.remove();
                        } else {
                            console.log('[PWA] User dismissed install');
                        }
                        deferredPrompt = null;
                    });
                }
            });

            iosBtn.addEventListener('click', function() {
                alert('To install:\n\n1. Tap the Share button (box with arrow) at the bottom\n2. Tap "Add to Home Screen"\n3. Tap "Add" in the top right');
            });

            modal.appendChild(icon);
            modal.appendChild(title);
            modal.appendChild(desc);
            modal.appendChild(installBtn);
            modal.appendChild(iosBtn);
            overlay.appendChild(modal);
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.remove();
                }
            });
            document.body.appendChild(overlay);
        }

        window.addEventListener('appinstalled', function() {
            deferredPrompt = null;
            localStorage.setItem(INSTALLED_KEY, '1');
            const overlay = document.getElementById('pwa-install-overlay');
            if (overlay) overlay.remove();
            console.log('[PWA] App installed successfully');
        });
    })();
</script>
