<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Book - {{ config('app.name', 'Bookshop') }}</title>
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
                                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="h-32 w-24 object-cover rounded-lg mx-auto mb-2">
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
                        <div class="mt-1 flex justify-center rounded-lg border-2 border-dashed {{ $book->is_free ? 'border-red-500' : 'border-gray-300' }} px-6 py-6" id="pdf-dropzone">
                            <div class="text-center" id="pdf-content">
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
                                <div class="mt-2 flex text-sm leading-6 text-gray-600">
                                    <label for="book_pdf" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 hover:text-indigo-500">
                                        <span>{{ $book->book_pdf ? 'Replace PDF' : 'Upload PDF' }}</span>
                                        <input id="book_pdf" name="book_pdf" type="file" class="sr-only" accept="application/pdf" onchange="updatePdfPreview(this)">
                                    </label>
                                    @if($book->pdf_file)
                                        <p class="pl-1">or drag and drop</p>
                                    @endif
                                </div>
                                <p class="text-xs leading-5 text-gray-500 mt-1">PDF up to 10MB</p>
                            </div>
                            <!-- PDF Preview -->
                            <div id="pdf-preview" class="hidden">
                                <svg class="mx-auto h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p id="pdf-name" class="mt-2 text-sm text-gray-500"></p>
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
    </body>
</html>