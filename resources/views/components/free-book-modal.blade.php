<!-- Free Book Download Modal -->
<div id="free-book-modal" class="fixed inset-0 hidden" style="z-index: 99999 !important;" onclick="closeFreeBookModal()">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative" onclick="event.stopPropagation()">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Get Your Free Book</h3>
                <p class="text-gray-600 mt-2" id="modal-book-title">Enter your details to download</p>
            </div>

            <form id="free-book-form" onsubmit="submitFreeBookForm(event)">
                <input type="hidden" id="modal-book-id" value="">

                <div class="space-y-4">
                    <div>
                        <label for="modal-full-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="modal-full-name" name="full_name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="John Doe">
                    </div>

                    <div>
                        <label for="modal-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" id="modal-email" name="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="john@example.com">
                    </div>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    We'll send you the download link and keep you updated with more professional books.
                </div>

                <button type="submit" id="modal-submit-btn"
                    class="w-full mt-6 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Send Me The Download Link
                </button>
            </form>

            <div id="modal-success" class="hidden text-center py-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Check Your Email!</h3>
                <p class="text-gray-600 mt-2">Your download link has been sent. Please check your inbox.</p>
            </div>
        </div>
    </div>
</div>

<script>
    let currentFreeBookId = null;

    function openFreeBookModal(bookId, bookTitle) {
        currentFreeBookId = bookId;
        document.getElementById('modal-book-id').value = bookId;
        document.getElementById('modal-book-title').textContent = bookTitle || 'Enter your details to download';
        document.getElementById('free-book-modal').classList.remove('hidden');
        document.getElementById('free-book-form').classList.remove('hidden');
        document.getElementById('modal-success').classList.add('hidden');
        document.getElementById('modal-full-name').value = '';
        document.getElementById('modal-email').value = '';
    }

    function closeFreeBookModal() {
        document.getElementById('free-book-modal').classList.add('hidden');
        currentFreeBookId = null;
    }

    async function submitFreeBookForm(event) {
        event.preventDefault();

        const bookId = document.getElementById('modal-book-id').value;
        const fullName = document.getElementById('modal-full-name').value;
        const email = document.getElementById('modal-email').value;
        const submitBtn = document.getElementById('modal-submit-btn');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Sending...';

        try {
            const response = await fetch('{{ route('free-book.lead') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    full_name: fullName,
                    email: email,
                    book_id: bookId,
                }),
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('free-book-form').classList.add('hidden');
                document.getElementById('modal-success').classList.remove('hidden');
            } else {
                alert(data.message || 'Something went wrong. Please try again.');
            }
        } catch (error) {
            alert('Network error. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Send Me The Download Link
            `;
        }
    }
</script>
