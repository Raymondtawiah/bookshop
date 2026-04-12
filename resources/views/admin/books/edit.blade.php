<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Book - {{ config('app.name', 'Bookshop') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        </script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 font-sans">
        <x-flash-message />
        
        <x-admin-navbar />
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Book</h1>
                    <p class="text-gray-500">Update book information</p>
                </div>
                <a href="{{ route('admin.books') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Books
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Author -->
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₵</span>
                                <input type="number" name="price" id="price" value="{{ old('price', $book->price) }}" step="0.01" min="0" required
                                    class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $book->category) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Pages -->
                        <div>
                            <label for="pages" class="block text-sm font-medium text-gray-700 mb-1">Pages</label>
                            <input type="number" name="pages" id="pages" value="{{ old('pages', $book->pages) }}" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Published Year -->
                        <div>
                            <label for="published_year" class="block text-sm font-medium text-gray-700 mb-1">Published Year</label>
                            <input type="number" name="published_year" id="published_year" value="{{ old('published_year', $book->published_year) }}" min="1000" max="2100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $book->stock) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                        <div class="mt-1 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-6" id="cover-image-dropzone">
                            <div class="text-center" id="cover-image-content">
                                @if($book->cover_image)
                                    <img src="{{ asset('books/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-32 w-24 object-cover rounded-lg mx-auto mb-2">
                                @else
                                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                                <div class="mt-2 flex text-sm leading-6 text-gray-600">
                                    <label for="cover_image" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 hover:text-indigo-500">
                                        <span>{{ $book->cover_image ? 'Replace image' : 'Upload a file' }}</span>
                                        <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*" onchange="updateCoverImagePreview(this)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                            </div>
                            <!-- Image Preview -->
                            <div id="cover-image-preview" class="hidden">
                                <img id="cover-preview-img" src="" alt="Cover Preview" class="max-h-48 rounded-lg mx-auto">
                                <p id="cover-image-name" class="mt-2 text-sm text-gray-500"></p>
                            </div>
                        </div>
                    </div>

<!-- PDF File -->
                    <div id="pdf-section" class="{{ $book->is_free ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            PDF File
                            <span id="pdf-required-indicator" class="text-red-500 text-xs {{ $book->is_free ? '' : 'hidden' }}">(Required for free books)</span>
                        </label>
                        <div class="mt-1 rounded-lg border-2 border-dashed {{ $book->is_free ? 'border-red-500' : 'border-gray-300' }} px-6 py-4 relative" id="pdf-dropzone">
                            <input id="book_pdf" name="book_pdf" type="file" class="hidden" accept="application/pdf" onchange="handleFileSelect(this)">
                            
                            <!-- Empty State -->
                            <div class="text-center" id="pdf-content" onclick="document.getElementById('book_pdf').click()">
                                @if($book->pdf_file)
                                    <svg class="mx-auto h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">{{ $book->book_pdf }}</p>
                                @else
                                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @endif
                                <div class="mt-2 flex text-sm leading-6 text-gray-600 justify-center">
                                    <span class="cursor-pointer rounded-md bg-white font-semibold text-indigo-600 hover:text-indigo-500">
                                        {{ $book->pdf_file ? 'Replace PDF' : 'Upload PDF' }}
                                    </span>
                                    @if($book->pdf_file)
                                        <p class="pl-1">or drag and drop</p>
                                    @endif
                                </div>
                                <p class="text-xs leading-5 text-gray-500 mt-1">PDF up to 10MB</p>
                            </div>
                            
                            <!-- Selected Files - Inside Dropzone -->
                            <div id="pdf-selected" class="hidden">
                                <div id="file-list" class="space-y-2"></div>
                                <label for="book_pdf" class="mt-3 cursor-pointer text-sm text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Replace file
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Featured -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-700">
                            Mark as featured book
                        </label>
                    </div>

                    <!-- Free Book -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_free" id="is_free" value="1" {{ old('is_free', $book->is_free) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="is_free" class="ml-2 block text-sm font-medium text-gray-700">
                            This book is free (PDF download)
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.books') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Update Book
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Bookshop Admin. All rights reserved.</p>
            </div>
        </footer>

        <script>
            function updateCoverImagePreview(input) {
                if (input.files && input.files[0]) {
                    var file = input.files[0];
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        document.getElementById('cover-preview-img').src = e.target.result;
                        document.getElementById('cover-image-name').textContent = file.name;
                        document.getElementById('cover-image-content').classList.add('hidden');
                        document.getElementById('cover-image-preview').classList.remove('hidden');
                        document.getElementById('cover-image-dropzone').classList.remove('border-gray-300');
                        document.getElementById('cover-image-dropzone').classList.add('border-green-500');
                    }
                    
                    reader.readAsDataURL(file);
                }
            }

            function updatePdfPreview(input) {
                if (input.files && input.files[0]) {
                    var file = input.files[0];
                    document.getElementById('pdf-name').textContent = file.name;
                    document.getElementById('pdf-content').classList.add('hidden');
                    document.getElementById('pdf-preview').classList.remove('hidden');
                    document.getElementById('pdf-dropzone').classList.remove('border-gray-300');
                    document.getElementById('pdf-dropzone').classList.add('border-green-500');
                }
            }

            // Mutual exclusivity: Featured vs Free
            const isFeaturedCheckbox = document.getElementById('is_featured');
            const isFreeCheckbox = document.getElementById('is_free');
            const priceInput = document.getElementById('price');
            const pdfSection = document.getElementById('pdf-section');
            const pdfRequiredIndicator = document.getElementById('pdf-required-indicator');
            const pdfDropzone = document.getElementById('pdf-dropzone');

            isFeaturedCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    isFreeCheckbox.checked = false;
                    if (priceInput) priceInput.readOnly = false;
                    if (pdfSection) pdfSection.classList.add('hidden');
                }
            });

            isFreeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    isFeaturedCheckbox.checked = false;
                    if (priceInput) {
                        priceInput.value = 0;
                        priceInput.readOnly = true;
                    }
                    if (pdfSection) {
                        pdfSection.classList.remove('hidden');
                        if (pdfRequiredIndicator) {
                            pdfRequiredIndicator.classList.remove('hidden');
                            pdfDropzone.classList.remove('border-gray-300');
                            pdfDropzone.classList.add('border-red-500');
                        }
                    }
                } else {
                    if (priceInput) priceInput.readOnly = false;
                    if (pdfSection) pdfSection.classList.add('hidden');
                }
            });
        </script>

    <!-- PDF Preview Modal -->
    <div id="pdf-preview-modal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">PDF Preview</h3>
                    <button type="button" onclick="closePdfPreview()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-auto p-4 bg-gray-100">
                    <canvas id="pdf-preview-canvas" class="mx-auto shadow-lg"></canvas>
                </div>
                <div class="p-4 border-t border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="prevPdfPage()" class="p-2 hover:bg-gray-100 rounded-lg" id="prev-page-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <span class="text-sm text-gray-600" id="page-indicator">Page 1</span>
                        <button type="button" onclick="nextPdfPage()" class="p-2 hover:bg-gray-100 rounded-lg" id="next-page-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <button type="button" onclick="confirmPdfReview()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                        ✓ Confirm Review
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPdfDoc = null;
        let currentPdfPage = 1;
        let currentReviewIndex = null;
        let selectedFiles = [];
        let reviewedFiles = new Set();

        function handleFileSelect(input) {
            const files = Array.from(input.files);
            files.forEach(file => {
                selectedFiles.push({
                    index: selectedFiles.length,
                    name: file.name,
                    size: (file.size / 1024).toFixed(1) + ' KB',
                    type: file.type || 'application/pdf',
                    file: file,
                    reviewed: false
                });
            });
            renderFileList();
            input.value = '';
        }

        function renderFileList() {
            const fileList = document.getElementById('file-list');
            const pdfContent = document.getElementById('pdf-content');
            const pdfSelected = document.getElementById('pdf-selected');
            
            if (selectedFiles.length > 0) {
                pdfContent.classList.add('hidden');
                pdfSelected.classList.remove('hidden');
                document.getElementById('pdf-dropzone').classList.remove('py-6');
                document.getElementById('pdf-dropzone').classList.add('py-4');
            } else {
                pdfContent.classList.remove('hidden');
                pdfSelected.classList.add('hidden');
                document.getElementById('pdf-dropzone').classList.add('py-6');
                document.getElementById('pdf-dropzone').classList.remove('py-4');
            }
            
            if (selectedFiles.length === 0) {
                fileList.innerHTML = '';
                return;
            }

            fileList.innerHTML = `
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    ${selectedFiles.map((fileData) => `
                        <div class="relative bg-white rounded-lg border-2 border-gray-200 p-3 flex flex-col items-center justify-center aspect-square" id="file-item-${fileData.index}">
                            <svg class="w-10 h-10 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs font-medium text-gray-900 text-center truncate w-full px-1" title="${fileData.name}">${fileData.name}</p>
                            <p class="text-xs text-gray-500">${fileData.size}</p>
                            
                            ${fileData.reviewed ? `
                                <div class="mt-2 text-center">
                                    <span class="text-green-600 text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Reviewed
                                    </span>
                                </div>
                            ` : `
                                <button type="button" onclick="reviewFile(${fileData.index})" class="mt-2 bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">
                                    Review
                                </button>
                            `}
                            
                            <button type="button" onclick="removeFile(${fileData.index})" class="absolute top-1 right-1 text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        async function reviewFile(index) {
            const fileData = selectedFiles.find(f => f.index === index);
            if (!fileData) return;
            
            currentReviewIndex = index;
            currentPdfPage = 1;
            
            document.getElementById('pdf-preview-modal').classList.remove('hidden');
            
            try {
                const arrayBuffer = await fileData.file.arrayBuffer();
                currentPdfDoc = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                
                document.getElementById('page-indicator').textContent = `Page 1 of ${currentPdfDoc.numPages}`;
                document.getElementById('prev-page-btn').disabled = true;
                document.getElementById('next-page-btn').disabled = currentPdfDoc.numPages === 1;
                
                renderPdfPage(currentPdfPage);
            } catch (error) {
                console.error('Error loading PDF:', error);
                alert('Error loading PDF file. Please try again.');
                closePdfPreview();
            }
        }

        async function renderPdfPage(pageNum) {
            const canvas = document.getElementById('pdf-preview-canvas');
            const ctx = canvas.getContext('2d');
            
            const page = await currentPdfDoc.getPage(pageNum);
            const viewport = page.getViewport({ scale: 1.5 });
            
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            
            await page.render({
                canvasContext: ctx,
                viewport: viewport
            }).promise;
            
            document.getElementById('page-indicator').textContent = `Page ${pageNum} of ${currentPdfDoc.numPages}`;
            document.getElementById('prev-page-btn').disabled = pageNum <= 1;
            document.getElementById('next-page-btn').disabled = pageNum >= currentPdfDoc.numPages;
        }

        function prevPdfPage() {
            if (currentPdfPage > 1) {
                currentPdfPage--;
                renderPdfPage(currentPdfPage);
            }
        }

        function nextPdfPage() {
            if (currentPdfDoc && currentPdfPage < currentPdfDoc.numPages) {
                currentPdfPage++;
                renderPdfPage(currentPdfPage);
            }
        }

        function closePdfPreview() {
            document.getElementById('pdf-preview-modal').classList.add('hidden');
            currentPdfDoc = null;
            currentReviewIndex = null;
        }

        function confirmPdfReview() {
            if (currentReviewIndex !== null) {
                const fileData = selectedFiles.find(f => f.index === currentReviewIndex);
                if (fileData) {
                    fileData.reviewed = true;
                    reviewedFiles.add(currentReviewIndex);
                }
                renderFileList();
            }
            closePdfPreview();
        }

        function removeFile(index) {
            selectedFiles = selectedFiles.filter(f => f.index !== index);
            reviewedFiles.delete(index);
            renderFileList();
        }

        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('pdf-preview-modal').classList.contains('hidden')) {
                if (e.key === 'Escape') {
                    closePdfPreview();
                } else if (e.key === 'ArrowLeft') {
                    prevPdfPage();
                } else if (e.key === 'ArrowRight') {
                    nextPdfPage();
                }
            }
        });

        // Override form submit to include files
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (selectedFiles.length > 0 && reviewedFiles.size === 0) {
                alert('Please review all files before submitting.');
                return;
            }
            
            const formData = new FormData(this);
            
            // Clear the original book_pdf from form
            formData.delete('book_pdf');
            
            // Add all files from our array
            selectedFiles.forEach((fileData) => {
                formData.append('book_pdf', fileData.file);
            });
            
            // Clear the original file input
            const pdfInput = document.getElementById('book_pdf');
            if (pdfInput) pdfInput.value = '';
            
            // Send via fetch
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '{{ route("admin.books") }}';
                } else {
                    return response.text().then(text => {
                        alert('Error: ' + text);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during upload');
            });
        });
    </script>
    </body>
</html>