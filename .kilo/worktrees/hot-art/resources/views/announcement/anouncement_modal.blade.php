<!-- Announcement Modal -->
<div id="announcement-modal" class="fixed inset-0 z-[99999]" style="z-index: 999999 !important;">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAnnouncementModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4 pt-14">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative max-h-[90vh] overflow-hidden">
            <div class="p-8 overflow-y-auto max-h-[90vh] rounded-2xl">
                <button onclick="closeAnnouncementModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
                        <img src="{{ asset('announcement_icon.jpg') }}" alt="Announcement" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">🌍 Global Visa Updates (June 2026)</h3>
                </div>

                <div class="space-y-6 text-left">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <h4 class="font-bold text-lg text-gray-900 mb-2">🇺🇸 United States</h4>
                        <p class="text-gray-700"><strong>Green Card Retrogression:</strong> The newly released June 2026 Visa Bulletin shows heavy setbacks for applicants from India, with EB-1 retrogressing by 3.5 months and EB-2 rolling back by over 10 months.</p>
                        <p class="text-gray-700 mt-2"><strong>Emergency Service Pauses:</strong> U.S. Embassies in Uganda, South Sudan, and the DRC have completely paused visa operations due to strict, newly implemented Ebola-related travel health restrictions.</p>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-4">
                        <h4 class="font-bold text-lg text-gray-900 mb-2">🇪🇺 Europe & 🇳🇿 Oceania</h4>
                        <p class="text-gray-700"><strong>Ireland Appeals Axed:</strong> Effective June 1, Ireland has permanently eliminated the right to appeal standard short-stay (Type C) business and tourist visa refusals in order to force faster re-applications.</p>
                        <p class="text-gray-700 mt-2"><strong>New Zealand Goes Digital:</strong> As of June 1, all applications for family members of temporary visa holders have transitioned to an enhanced digital-only platform using automated passport readers.</p>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <button onclick="closeAnnouncementModal()" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md">
                        Got It
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showAnnouncementModal() {
        const modal = document.getElementById('announcement-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeAnnouncementModal() {
        const modal = document.getElementById('announcement-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
        sessionStorage.setItem('announcement-closed', 'true');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (!sessionStorage.getItem('announcement-closed')) {
            setTimeout(showAnnouncementModal, 500);
        }
    });
</script>
