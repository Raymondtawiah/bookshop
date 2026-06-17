<script>
    (function() {
        if (window.matchMedia('(display-mode: standalone)').matches ||
            window.matchMedia('display-mode: fullscreen').matches ||
            window.matchMedia('display-mode: minimal-ui').matches ||
            window.navigator.standalone === true) {
            return;
        }

        const STORAGE_KEY = 'pwa_install_dismissed';
        const THIRTY_MINUTES = 30 * 60 * 1000;

        if (localStorage.getItem(STORAGE_KEY)) return;

        let deferredPrompt = null;
        let promptShown = false;

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            console.log('[PWA] Install prompt captured');

            if (!promptShown) {
                const elapsed = Date.now() - (parseInt(localStorage.getItem('pwa_first_visit') || '0') || Date.now());
                const timeSinceFirstVisit = parseInt(localStorage.getItem('pwa_first_visit'));

                if (!timeSinceFirstVisit) {
                    localStorage.setItem('pwa_first_visit', Date.now());
                }

                const remaining = THIRTY_MINUTES - (Date.now() - timeSinceFirstVisit);
                if (remaining > 0 && timeSinceFirstVisit) {
                    setTimeout(showInstallPrompt, remaining);
                } else {
                    setTimeout(showInstallPrompt, 1000);
                }
                promptShown = true;
            }
        });

        if (!localStorage.getItem('pwa_first_visit')) {
            localStorage.setItem('pwa_first_visit', Date.now());
        }

        const firstVisit = parseInt(localStorage.getItem('pwa_first_visit'));
        if (firstVisit && !promptShown) {
            const elapsed = Date.now() - firstVisit;
            const remaining = THIRTY_MINUTES - elapsed;
            if (remaining <= 0) {
                setTimeout(showInstallPrompt, 1000);
                promptShown = true;
            } else {
                setTimeout(showInstallPrompt, remaining);
                promptShown = true;
            }
        }

        window.addEventListener('appinstalled', function() {
            deferredPrompt = null;
            console.log('[PWA] App installed successfully');
        });

        function showInstallPrompt() {
            if (deferredPrompt || /iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                showInstallUI();
            }
        }

        function showInstallUI() {
            if (document.getElementById('pwa-install-banner')) return;

            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
            const banner = document.createElement('div');
            banner.id = 'pwa-install-banner';
            Object.assign(banner.style, {
                position: 'fixed',
                bottom: '20px',
                left: '50%',
                transform: 'translateX(-50%)',
                background: '#1f2937',
                color: '#fff',
                padding: '16px 24px',
                borderRadius: '12px',
                boxShadow: '0 10px 25px rgba(0,0,0,0.3)',
                zIndex: '9999',
                display: 'flex',
                alignItems: 'center',
                gap: '12px',
                maxWidth: '90vw',
                flexWrap: 'wrap',
                justifyContent: 'center',
                fontFamily: 'system-ui, -apple-system, sans-serif'
            });

            const icon = document.createElement('div');
            Object.assign(icon.style, {
                width: '40px',
                height: '40px',
                background: 'linear-gradient(135deg, #4f46e5, #7c3aed)',
                borderRadius: '10px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: '20px',
                flexShrink: '0'
            });
            icon.textContent = '📚';

            const text = document.createElement('div');
            Object.assign(text.style, { textAlign: 'left' });
            text.innerHTML = '<div style="font-weight:600;font-size:14px;">Install BookShop App</div>' +
                '<div style="font-size:12px;opacity:0.8;">Get instant access & offline reading</div>';

            const buttons = document.createElement('div');
            buttons.style.display = 'flex';
            buttons.style.gap = '8px';

            const installBtn = document.createElement('button');
            installBtn.textContent = isIOS ? 'How to Install' : 'Install Now';
            Object.assign(installBtn.style, {
                background: '#fff',
                color: '#1f2937',
                border: 'none',
                padding: '8px 16px',
                borderRadius: '8px',
                fontWeight: '600',
                cursor: 'pointer',
                fontSize: '13px'
            });
            installBtn.addEventListener('click', function() {
                if (isIOS) {
                    alert('To install:\n\n1. Tap the Share button in Safari\n2. Tap "Add to Home Screen"\n3. Tap "Add"');
                } else if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(function(choiceResult) {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('[PWA] User accepted install');
                        } else {
                            console.log('[PWA] User dismissed install');
                        }
                        deferredPrompt = null;
                        banner.remove();
                    });
                }
            });

            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '✕';
            Object.assign(closeBtn.style, {
                background: 'transparent',
                border: 'none',
                color: '#fff',
                cursor: 'pointer',
                fontSize: '18px',
                opacity: '0.7',
                padding: '4px 8px'
            });
            closeBtn.addEventListener('click', function() {
                banner.remove();
                localStorage.setItem(STORAGE_KEY, '1');
            });

            buttons.appendChild(installBtn);
            buttons.appendChild(closeBtn);
            banner.appendChild(icon);
            banner.appendChild(text);
            banner.appendChild(buttons);
            document.body.appendChild(banner);
        }
    })();
</script>
