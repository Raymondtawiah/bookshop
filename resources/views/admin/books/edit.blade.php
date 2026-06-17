<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Book - {{ config('app.name', 'Bookshop') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <style>
            .input-field {
                @apply w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all;
            }
            .btn-primary {
                @apply px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200;
            }
            .btn-danger {
                @apply px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-2xl font-bold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg shadow-red-200;
            }
        </style>
    </head>
    <body class="bg-gray-50 font-sans pt-20">
        <x-flash-message />
        
        <x-admin-navbar />
        
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900">Edit Book</h1>
                        <p class="text-gray-500 font-medium">Update book information</p>
                    </div>
                </div>
                <a href="{{ route('admin.books') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>

            <!-- Book Type Info -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-6 mb-6">
                <div class="flex items-center gap-4">
                    @if($book->is_free && $book->book_pdf)
                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">PDF Book (Free)</p>
                            <p class="text-sm text-gray-500">This is a free PDF download</p>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">Book Cover (Physical)</p>
                            <p class="text-sm text-gray-500">This is a physical book for sale</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($book->is_free && $book->book_pdf)
            <!-- PDF Book Form -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">PDF Book Details</h2>
                
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="book_type" value="pdf">
                    <input type="hidden" name="is_free" value="1">
                    <input type="hidden" name="price" value="0">
                    
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl font-medium">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                                class="input-field">
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-bold text-gray-700 mb-2">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required
                                class="input-field">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="input-field resize-none">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Cover Image</label>
                        <div class="text-center">
                            @if($book->cover_image && file_exists(public_path('books/' . $book->cover_image)))
                                <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="max-h-48 rounded-xl mx-auto mb-3 shadow-sm">
                            @else
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                            <div class="mt-3">
                            <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">PDF File</label>
                        <input id="book_pdfs" name="book_pdfs" type="file" class="hidden" accept=".pdf" onchange="handlePdfSelect(this)">
                        <div class="text-center cursor-pointer" onclick="document.getElementById('book_pdfs').click()">
                            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                <span class="font-medium text-indigo-600">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PDF up to 10MB</p>
                            @if($book->book_pdf)
                            <p class="text-sm text-green-600 mt-2 font-medium">Current: {{ $book->book_pdf }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_featured" class="ml-3 block text-sm font-bold text-gray-700">
                            Mark as featured
                        </label>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="btn-danger flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update PDF Book
                        </button>
                    </div>
                </form>
            </div>
            @else
            <!-- Book Cover Form -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Book Cover Details</h2>
                
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="book_type" value="cover">
                    
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl font-medium">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                                class="input-field">
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-bold text-gray-700 mb-2">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required
                                class="input-field">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-bold text-gray-700 mb-2">Price ($) *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                                <input type="number" name="price" id="price" value="{{ old('price', $book->price) }}" step="0.01" min="0" required
                                    class="input-field pl-8">
                            </div>
                        </div>
                        <div>
                            <label for="isbn" class="block text-sm font-bold text-gray-700 mb-2">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}"
                                class="input-field">
                        </div>
                        <div>
                            <label for="pages" class="block text-sm font-bold text-gray-700 mb-2">Pages</label>
                            <input type="number" name="pages" id="pages" value="{{ old('pages', $book->pages) }}" min="1"
                                class="input-field">
                        </div>
                        <div>
                            <label for="published_year" class="block text-sm font-bold text-gray-700 mb-2">Published Year</label>
                            <input type="number" name="published_year" id="published_year" value="{{ old('published_year', $book->published_year) }}" min="1000" max="2100"
                                class="input-field">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="input-field resize-none">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Cover Image</label>
                        <div class="text-center">
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="max-h-48 rounded-xl mx-auto mb-3 shadow-sm">
                            @else
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                            <div class="mt-3">
                            <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_featured" class="ml-3 block text-sm font-bold text-gray-700">
                            Mark as featured book
                        </label>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="btn-primary flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
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