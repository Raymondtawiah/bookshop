<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Bookshop Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" href="/favicon.ico" sizes="any">
</head>
<body class="bg-gray-50 font-sans">
    <x-flash-message />
    
    <x-admin-navbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="mb-6">
                <a href="{{ route('admin.orders') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Orders
                </a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-3 sm:px-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Order #{{ $order->order_number ?? $order->id }}</h2>
                        <span class="mt-2 sm:mt-0 px-2 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap
                            @if($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="px-4 py-4 sm:px-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Customer Information</h3>
                            <dl class="space-y-2 sm:space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-gray-900">{{ $order->customer_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-gray-900">{{ $order->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Contact</dt>
                                    <dd class="text-gray-900">{{ $order->contact }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nationality</dt>
                                    <dd class="text-gray-900">{{ $order->nationality ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Delivery Address</dt>
                                    <dd class="text-gray-900">{{ $order->residence ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                    <dd class="text-gray-900">
                                        @if($order->payment_method === 'momo')
                                            Mobile Money
                                        @elseif($order->payment_method === 'bank')
                                            Bank Transfer
                                        @else
                                            Card Payment
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                    <dd class="text-gray-900">
                                        @if($order->payment_status === 'paid')
                                            <span class="text-green-600 font-medium">Paid</span>
                                        @elseif($order->payment_status === 'pending')
                                            <span class="text-yellow-600 font-medium">Pending</span>
                                        @else
                                            <span class="text-red-600 font-medium">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                    <dd class="text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                                    <dd class="text-xl font-bold text-indigo-600">₵{{ number_format($order->total_amount, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ordered Books</h3>
                        @php
                            $items = $order->order_items;
                        @endphp
                        @if(!empty($items) && $items->count() > 0)
                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Book</th>
                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Price</th>
                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Qty</th>
                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $item)
                                    <tr>
                                        <td class="px-2 py-3">
                                            <div class="text-sm font-medium text-gray-900">{{ is_array($item) ? ($item['product_name'] ?? 'Unknown') : 'Unknown' }}</div>
                                            @if(is_array($item) ? ($item['book_id'] ?? null) : (isset($item->book_id) ? $item->book_id : null))
                                                <div class="text-xs text-gray-500">Book ID: {{ is_array($item) ? ($item['book_id'] ?? '') : ($item->book_id ?? '') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-2 py-3 text-sm text-gray-900 whitespace-nowrap">₵{{ number_format(is_array($item) ? ($item['product_price'] ?? 0) : ($item->product_price ?? 0), 2) }}</td>
                                        <td class="px-2 py-3 text-sm text-gray-900 whitespace-nowrap">{{ is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1) }}</td>
                                        <td class="px-2 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">₵{{ number_format((is_array($item) ? ($item['product_price'] ?? 0) : ($item->product_price ?? 0)) * (is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1)), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-500 text-sm">No order items found.</p>
                        @endif
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order Status</h3>
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                                <select name="status" id="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                                <select name="payment_status" id="payment_status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Upload Word File and Convert to PDF -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Word Document to PDF</h3>
                        <p class="text-sm text-gray-600 mb-4">Upload Word documents - review each file before submitting.</p>
                        
                        <form action="{{ route('admin.orders.uploadWordPdf', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="prepareFormForSubmit(event)">
                            @csrf
                            
                            <!-- Hidden input to store selected files -->
                            <input type="file" name="word_file[]" id="word_file_submit" class="hidden" multiple>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Word Document(s)</label>
                                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-500 transition-colors">
                                    <input type="file" id="word_file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                        class="hidden"
                                        multiple
                                        onchange="handleFileSelect(this)">
                                    
                                    <div id="files-container" class="space-y-2">
                                        <label for="word_file" class="cursor-pointer block">
                                            <div class="text-gray-500 py-4">
                                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                <p class="text-sm">Drop files here or click to browse</p>
                                                <p class="text-xs text-gray-400 mt-1">Accepted formats: .doc, .docx (max 10MB each)</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="submit-btn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload & Send as PDF
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="openDeleteModal{{ $order->id }}()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Order
                        </button>
                    </div>
                </div>
            </div>
    </main>

    <!-- Delete Modal -->
    <x-modal-delete 
        :id="$order->id" 
        :title="'Delete Order'" 
        :message="'Are you sure you want to delete this order? This action cannot be undone.'" 
        :action="route('admin.orders.destroy', $order->id)"
        confirmText="Delete"
    />

    <script>
        let selectedFiles = [];
        let reviewedFiles = new Set();

        function handleFileSelect(input) {
            const files = Array.from(input.files);
            if (files.length === 0) {
                return;
            }

            const existingFileNames = selectedFiles.map(f => f.name);
            const existingCount = existingFileNames.length;
            
            files.forEach(file => {
                if (!existingFileNames.includes(file.name)) {
                    selectedFiles.push(file);
                }
            });
            
            if (existingCount > 0) {
                const oldReviewed = new Set(reviewedFiles);
                reviewedFiles = new Set();
                selectedFiles.forEach((file, newIndex) => {
                    if (newIndex < existingCount && oldReviewed.has(newIndex)) {
                        reviewedFiles.add(newIndex);
                    }
                });
            }
            
            input.value = '';
            
            renderFilesInDropZone();
            updateSubmitButton();
        }

        function renderFilesInDropZone() {
            const container = document.getElementById('files-container');
            
            if (selectedFiles.length === 0) {
                container.innerHTML = `
                    <label for="word_file" class="cursor-pointer block">
                        <div class="text-gray-500 py-4">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm">Drop files here or click to browse</p>
                            <p class="text-xs text-gray-400 mt-1">Accepted formats: .doc, .docx (max 10MB each)</p>
                        </div>
                    </label>
                `;
                return;
            }

            container.innerHTML = `
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    ${selectedFiles.map((file, index) => `
                        <div class="relative bg-white rounded-lg border-2 border-gray-200 p-3 flex flex-col items-center justify-center aspect-square">
                            <svg class="w-10 h-10 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-xs font-medium text-gray-900 text-center truncate w-full px-1" title="${file.name}">${file.name}</p>
                            <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
                            
                            ${reviewedFiles.has(index) ? `
                                <div class="mt-2 text-center">
                                    <span class="text-green-600 text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Reviewed
                                    </span>
                                </div>
                            ` : `
                                <button type="button" onclick="reviewFile(${index})" class="mt-2 bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">
                                    Review
                                </button>
                            `}
                            
                            <button type="button" onclick="removeFile(${index})" class="absolute top-1 right-1 text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    `).join('')}
                </div>
                <label for="word_file" class="cursor-pointer block mt-3">
                    <span class="text-sm text-indigo-600 hover:text-indigo-800">+ Add more files</span>
                </label>
            `;
        }

        function reviewFile(index) {
            const file = selectedFiles[index];
            const container = document.getElementById('files-container');
            
            container.innerHTML = `
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    ${selectedFiles.map((f, i) => `
                        <div class="relative bg-white rounded-lg border-2 ${i === index ? 'border-indigo-500' : 'border-gray-200'} p-3 flex flex-col items-center justify-center aspect-square ${i === index ? 'ring-2 ring-indigo-300' : ''}">
                            <svg class="w-10 h-10 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-xs font-medium text-gray-900 text-center truncate w-full px-1" title="${f.name}">${f.name}</p>
                            <p class="text-xs text-gray-500">${(f.size / 1024).toFixed(1)} KB</p>
                            
                            ${reviewedFiles.has(i) ? `
                                <div class="mt-2 text-center">
                                    <span class="text-green-600 text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Reviewed
                                    </span>
                                </div>
                            ` : i === index ? `
                                <button type="button" onclick="confirmReview(${i})" class="mt-2 bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                    ✓ Confirm
                                </button>
                            ` : `
                                <button type="button" onclick="reviewFile(${i})" class="mt-2 bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">
                                    Review
                                </button>
                            `}
                            
                            ${i === index ? '' : `
                                <button type="button" onclick="removeFile(${i})" class="absolute top-1 right-1 text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            `}
                        </div>
                    `).join('')}
                </div>
                <label for="word_file" class="cursor-pointer block mt-3">
                    <span class="text-sm text-indigo-600 hover:text-indigo-800">+ Add more files</span>
                </label>
            `;
        }

        function confirmReview(index) {
            reviewedFiles.add(index);
            renderFilesInDropZone();
            updateSubmitButton();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            reviewedFiles = new Set(Array.from(reviewedFiles).map(i => i > index ? i - 1 : i).filter(i => i < selectedFiles.length));
            
            if (selectedFiles.length === 0) {
                document.getElementById('word_file').value = '';
            }
            
            renderFilesInDropZone();
            updateSubmitButton();
        }

        function clearFileList() {
            selectedFiles = [];
            reviewedFiles = new Set();
            document.getElementById('word_file').value = '';
            renderFilesInDropZone();
            updateSubmitButton();
        }

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submit-btn');
            const allReviewed = selectedFiles.length > 0 && reviewedFiles.size === selectedFiles.length;
            submitBtn.disabled = !allReviewed;
        }

        function prepareFormForSubmit(event) {
            event.preventDefault();
            
            if (selectedFiles.length === 0) {
                alert('Please select at least one Word document.');
                return;
            }

            const hiddenInput = document.getElementById('word_file_submit');
            const dataTransfer = new DataTransfer();
            
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            
            hiddenInput.files = dataTransfer.files;
            event.target.submit();
        }
    </script>
</body>
</html>