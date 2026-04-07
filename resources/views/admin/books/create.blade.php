<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Add New Book - {{ config('app.name', 'Bookshop') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 font-sans">
        <x-flash-message />
        
        <x-admin-navbar />
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Add New Book</h1>
                    <p class="text-gray-500">Upload a new book to your inventory</p>
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
                <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Author -->
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('author')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₵</span>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                                    class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="e.g., Fiction, Science"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Pages -->
                        <div>
                            <label for="pages" class="block text-sm font-medium text-gray-700 mb-1">Pages</label>
                            <input type="number" name="pages" id="pages" value="{{ old('pages') }}" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Published Year -->
                        <div>
                            <label for="published_year" class="block text-sm font-medium text-gray-700 mb-1">Published Year</label>
                            <input type="number" name="published_year" id="published_year" value="{{ old('published_year') }}" min="1000" max="2100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                        <div class="mt-1 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-8" id="cover-image-dropzone">
                            <div class="text-center" id="cover-image-content">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                    <label for="cover_image" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 hover:text-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*" onchange="updateCoverImagePreview(this)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                            <!-- Image Preview -->
                            <div id="cover-image-preview" class="hidden">
                                <img id="cover-preview-img" src="" alt="Cover Preview" class="max-h-48 rounded-lg mx-auto">
                                <p id="cover-image-name" class="mt-2 text-sm text-gray-500"></p>
                            </div>
                        </div>
                    </div>

                    <!-- PDF Files -->
                    <div id="pdf-section" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            PDF Files 
                            <span id="pdf-required-indicator" class="text-red-500 text-xs hidden">(Required for free books)</span>
                        </label>
                        <div class="mt-1 rounded-lg border-2 border-dashed border-gray-300 px-6 py-16 cursor-pointer hover:border-indigo-400" id="pdf-dropzone" onclick="document.getElementById('book_pdfs').click()">
                            <div class="text-center" id="pdf-content">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="mt-4 text-sm leading-6 text-gray-600">
                                    <label for="book_pdfs" class="cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 inline-block">
                                        <span class="inline-flex items-center px-6 py-3 border-2 border-indigo-300 border-dashed rounded-lg hover:bg-indigo-50 text-base">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Select Files
                                        </span>
                                    </label>
                                    <input id="book_pdfs" name="book_pdfs[]" type="file" class="hidden" accept=".pdf" multiple onchange="handleFilesSelect(this)">
                                    <p class="mt-3">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-500 mt-2">PDF, DOC, DOCX up to 10MB each</p>
                            </div>
                        </div>
                        
                        <!-- Selected Files List -->
                        <div id="file-list" class="mt-4 space-y-3"></div>
                        
                        <!-- Hidden input for reviewed files -->
                        <input type="hidden" name="reviewed_files" id="reviewed_files" value="">
                    </div>

                    <!-- Featured -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-700">
                            Mark as featured book
                        </label>
                    </div>

                    <!-- Free Book -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_free" id="is_free" value="1" {{ old('is_free') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="is_free" class="ml-2 block text-sm font-medium text-gray-700">
                            This book is free (PDF download)
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Add Book
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
            // Store selected files
            let selectedFiles = [];
            let reviewedFiles = new Set();

            function handleFilesSelect(input) {
                const files = Array.from(input.files);
                
                // Get current file count to calculate proper indices
                const startIndex = selectedFiles.length;
                let addedCount = 0;
                
                files.forEach(file => {
                    const fileData = {
                        index: startIndex + addedCount,
                        name: file.name,
                        size: (file.size / 1024 / 1024).toFixed(2) + ' MB',
                        type: file.name.split('.').pop().toUpperCase(),
                        reviewed: true,
                        file: file
                    };
                    selectedFiles.push(fileData);
                    addedCount++;
                });
                
                renderFileList();
            }

            function renderFileList() {
                const fileList = document.getElementById('file-list');
                const reviewedInput = document.getElementById('reviewed_files');
                const pdfContent = document.getElementById('pdf-content');
                
                // Show/hide the upload area based on whether we have files
                if (selectedFiles.length > 0) {
                    pdfContent.classList.add('hidden');
                } else {
                    pdfContent.classList.remove('hidden');
                }
                
                if (selectedFiles.length === 0) {
                    fileList.innerHTML = '';
                    return;
                }

                fileList.innerHTML = selectedFiles.map((fileData) => `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200" id="file-item-${fileData.index}">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${fileData.name}</p>
                                <p class="text-xs text-gray-500">${fileData.size} • ${fileData.type}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            ${!fileData.reviewed ? `
                                <button type="button" onclick="reviewFile(${fileData.index})" 
                                    class="px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-md transition-colors">
                                    Review
                                </button>
                            ` : `
                                <span class="flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-green-700 bg-green-100 rounded-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Reviewed
                                </span>
                            `}
                            <button type="button" onclick="removeFile(${fileData.index})" 
                                class="p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `).join('');

                // Update hidden input with reviewed file indices
                reviewedInput.value = JSON.stringify(Array.from(reviewedFiles));
            }

            function reviewFile(index) {
                const fileData = selectedFiles.find(f => f.index === index);
                if (fileData) {
                    fileData.reviewed = true;
                    reviewedFiles.add(index);
                }
                renderFileList();
            }

            function removeFile(index) {
                selectedFiles = selectedFiles.filter(f => f.index !== index);
                reviewedFiles.delete(index);
                renderFileList();
            }

            // Override form submit to include files
            document.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Check if any files are selected
                if (selectedFiles.length > 0 && reviewedFiles.size === 0) {
                    alert('Please review all files before submitting.');
                    return;
                }
                
                // Create FormData from the form
                const formData = new FormData(this);
                
                // Clear the original book_pdfs from form
                formData.delete('book_pdfs[]');
                formData.delete('reviewed_files');
                
                // Add all files from our array
                selectedFiles.forEach((fileData) => {
                    formData.append('book_pdfs[]', fileData.file);
                });
                
                // Add reviewed files indices
                formData.append('reviewed_files', JSON.stringify(Array.from(reviewedFiles)));
                
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
    </body>
</html>