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
        
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    Back
                </a>
            </div>

            <!-- Book Type Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center gap-3">
                    @if($book->is_free && $book->book_pdf)
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">PDF Book (Free)</p>
                            <p class="text-sm text-gray-500">This is a free PDF download</p>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Book Cover (Physical)</p>
                            <p class="text-sm text-gray-500">This is a physical book for sale</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($book->is_free && $book->book_pdf)
            <!-- PDF Book Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">PDF Book Details</h2>
                
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="book_type" value="pdf">
                    <input type="hidden" name="is_free" value="1">
                    <input type="hidden" name="price" value="0">
                    
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $book->category) }}" placeholder="e.g., Fiction"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                        <div class="mt-1 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-6" id="cover-dropzone">
                            <div class="text-center" id="cover-content">
                                @if($book->cover_image && file_exists(public_path('books/' . $book->cover_image)))
                                    <img src="{{ asset('public/books/' . $book->cover_image) }}" alt="{{ $book->title }}" class="max-h-48 rounded-lg mx-auto mb-2">
                                @else
                                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                                <div class="mt-2 flex text-sm text-gray-600">
                                    <label for="pdf_cover_image" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500">
                                        <span>{{ ($book->cover_image && file_exists(public_path('books/' . $book->cover_image))) ? 'Replace' : 'Upload' }}</span>
                                        <input id="pdf_cover_image" name="cover_image" type="file" class="sr-only" accept="image/*" onchange="handleCoverImage(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PDF File</label>
                        <div class="mt-1 rounded-lg border-2 border-dashed border-gray-300 px-6 py-6" id="pdf-dropzone">
                            <input id="book_pdfs" name="book_pdfs" type="file" class="hidden" accept=".pdf" onchange="handlePdfSelect(this)">
                            <div class="text-center" id="pdf-content" onclick="document.getElementById('book_pdfs').click()">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-indigo-600">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500">PDF up to 10MB</p>
                                @if($book->book_pdf)
                                <p class="text-sm text-green-600 mt-2">Current: {{ $book->book_pdf }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-700">
                            Mark as featured
                        </label>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Update PDF Book
                        </button>
                    </div>
                </form>
            </div>
            @else
            <!-- Book Cover Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Book Cover Details</h2>
                
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="book_type" value="cover">
                    
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₵</span>
                                <input type="number" name="price" id="price" value="{{ old('price', $book->price) }}" step="0.01" min="0" required
                                    class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $book->category) }}" placeholder="e.g., Fiction"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="pages" class="block text-sm font-medium text-gray-700 mb-1">Pages</label>
                            <input type="number" name="pages" id="pages" value="{{ old('pages', $book->pages) }}" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="published_year" class="block text-sm font-medium text-gray-700 mb-1">Published Year</label>
                            <input type="number" name="published_year" id="published_year" value="{{ old('published_year', $book->published_year) }}" min="1000" max="2100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $book->stock ?? 0) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                        <div class="mt-1 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-6" id="cover-dropzone">
                            <div class="text-center" id="cover-content">
                                @if($book->cover_image && file_exists(public_path('books/' . $book->cover_image)))
                                    <img src="{{ asset('public/books/' . $book->cover_image) }}" alt="{{ $book->title }}" class="max-h-48 rounded-lg mx-auto mb-2">
                                @else
                                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                                <div class="mt-2 flex text-sm text-gray-600">
                                    <label for="cover_image" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500">
                                        <span>{{ ($book->cover_image && file_exists(public_path('books/' . $book->cover_image))) ? 'Replace' : 'Upload' }}</span>
                                        <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*" onchange="handleCoverImage(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-700">
                            Mark as featured
                        </label>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Update Book
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </main>

        <script>
            function handleCoverImage(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const dropzone = input.closest('.border-dashed');
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        dropzone.innerHTML = `
                            <div class="text-center">
                                <img src="${e.target.result}" alt="Cover Preview" class="max-h-48 rounded-lg mx-auto">
                                <p class="mt-2 text-sm text-gray-500">${file.name}</p>
                                <p class="text-xs text-green-600">Click to change</p>
                            </div>
                        `;
                        dropzone.classList.remove('border-gray-300');
                        dropzone.classList.add('border-green-500');
                    };
                    reader.readAsDataURL(file);
                }
            }

            function handlePdfSelect(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    document.getElementById('pdf-content').innerHTML = `
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="mt-2 text-sm font-medium text-gray-900">${file.name}</p>
                            <p class="text-xs text-gray-500">Click to change</p>
                        </div>
                    `;
                    document.getElementById('pdf-dropzone').classList.remove('border-gray-300');
                    document.getElementById('pdf-dropzone').classList.add('border-green-500');
                }
            }
        </script>
    </body>
</html>