<!-- Delete Confirmation Modal -->
<div id="deleteModal{{ $id }}" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal{{ $id }}()"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <!-- Close Button -->
            <button onclick="closeDeleteModal{{ $id }}()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Icon -->
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            
            <!-- Content -->
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">{{ $title }}</h3>
            <p class="text-gray-600 text-center mb-6">{{ $message }}</p>
            
            <!-- Actions -->
            <div class="flex gap-3">
                <button onclick="closeDeleteModal{{ $id }}()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <form action="{{ $action }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal{{ $id }}() {
    document.getElementById('deleteModal{{ $id }}').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal{{ $id }}() {
    document.getElementById('deleteModal{{ $id }}').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
