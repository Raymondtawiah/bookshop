<div id="cookie-consent" class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-lg z-[9999] hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-start gap-5 flex-1">
                <img src="{{ asset('icon.jpg') }}" alt="Visa with Nathaniel" class="w-12 h-12 rounded-xl shadow-md object-cover flex-shrink-0">
                <div class="flex-1">
                    <p class="text-base text-gray-700 leading-relaxed">
                        <strong>Visa with Nathaniel</strong> helps people prepare for visa interviews and access webinars, resources, and coaching to improve their chances of approval. 
                        We use cookies to remember your progress, support your learning experience, and keep your registration information secure. 
                        Your data is saved only to provide these services and is never sold to third parties. 
                        By clicking <strong>"Accept"</strong>, you agree to our use of cookies. 
                        <a href="{{ route('privacy') ?? '#' }}" class="text-indigo-600 hover:text-indigo-800 underline">Learn more</a>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <button onclick="handleCookieConsent(false)" class="px-5 py-2.5 text-base font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Reject
                </button>
                <button onclick="handleCookieConsent(true)" class="px-5 py-2.5 text-base font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    Accept
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes zoomIn {
      from {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
      }
      to {
        transform: scale(1) translateY(0);
        opacity: 1;
      }
    }
    
    @keyframes fadeOut {
      from {
        opacity: 1;
      }
      to {
        opacity: 0;
      }
    }
    
    .animate-zoom-in {
      animation: zoomIn 1.2s ease-out forwards;
    }
    
    .animate-fade-out {
      animation: fadeOut 0.8s ease-in forwards;
    }
</style>

<script>
    (function() {
        const consent = localStorage.getItem('cookie_consent');
        if (!consent) {
            const banner = document.getElementById('cookie-consent');
            if (banner) {
                banner.classList.remove('hidden');
                requestAnimationFrame(function() {
                    banner.classList.add('animate-zoom-in');
                });
            }
        }
    })();

    function handleCookieConsent(accepted) {
        localStorage.setItem('cookie_consent', accepted ? 'accepted' : 'rejected');
        const banner = document.getElementById('cookie-consent');
        if (banner) {
            banner.classList.remove('animate-zoom-in');
            banner.classList.add('animate-fade-out');
            setTimeout(function() {
                banner.classList.add('hidden');
            }, 800);
        }
    }
</script>
