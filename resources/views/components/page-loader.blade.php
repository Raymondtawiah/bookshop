<div id="page-loader" class="fixed inset-0 z-[99999] bg-white flex items-center justify-center" style="z-index: 99999 !important;">
    <div class="loader"></div>
</div>

<style>
    body.loading {
        overflow: hidden;
    }
    .loader {
        width: 50px;
        aspect-ratio: 1;
        display: grid;
        animation: l14 4s infinite;
    }
    .loader::before,
    .loader::after {
        content: "";
        grid-area: 1/1;
        border: 8px solid;
        border-radius: 50%;
        border-color: #6366f1 #6366f1 #0000 #0000;
        mix-blend-mode: darken;
        animation: l14 1s infinite linear;
    }
    .loader::after {
        border-color: #0000 #0000 #a855f7 #a855f7;
        animation-direction: reverse;
    }
    @keyframes l14 {
        100% {
            transform: rotate(1turn);
        }
    }
</style>

<script>
    (function() {
        var loader = document.getElementById('page-loader');
        if (!loader) return;

        document.body.classList.add('loading');

        function hideLoader() {
            document.body.classList.remove('loading');
            loader.style.transition = 'opacity 0.4s ease';
            loader.style.opacity = '0';
            setTimeout(function() {
                if (loader.parentNode) {
                    loader.remove();
                }
            }, 400);
        }

        function waitForContent(callback) {
            if (document.readyState === 'complete') {
                requestAnimationFrame(function() {
                    requestAnimationFrame(callback);
                });
            } else {
                window.addEventListener('load', function() {
                    requestAnimationFrame(function() {
                        requestAnimationFrame(callback);
                    });
                });
            }
        }

        waitForContent(function() {
            setTimeout(hideLoader, 300);
        });

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                hideLoader();
            }
        });
    })();
</script>
