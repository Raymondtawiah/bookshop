@extends('layouts.admin')

@section('title', 'Edit Book')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Book</h1>
                <p class="page-subtitle">Update book information</p>
            </div>
            <a href="{{ route('admin.books') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>

        <!-- Book Type Info -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Book Information</h2>
            </div>
            <div class="panel-body">
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
        </div>

        @if($book->is_free && $book->book_pdf)
        <!-- PDF Book Form -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">PDF Book Details</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="book_type" value="pdf">
                    <input type="hidden" name="is_free" value="1">
                    <input type="hidden" name="price" value="0">
                    
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl font-medium mb-6">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" class="resize-none">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Cover Image</label>
                        <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
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
                    </div>

                    <div class="form-group">
                        <label>PDF File</label>
                        <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="text-center">
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
                                <input id="book_pdfs" name="book_pdfs" type="file" accept=".pdf" onchange="handlePdfSelect(this)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mt-3">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl mb-6">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_featured" class="ml-3 block text-sm font-bold text-gray-700">
                            Mark as featured
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-2xl font-bold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg shadow-red-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update PDF Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <!-- Book Cover Form -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Book Cover Details</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="book_type" value="cover">
                    
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl font-medium mb-6">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                                <input type="number" name="price" id="price" value="{{ old('price', $book->price) }}" step="0.01" min="0" required class="pl-8">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}">
                        </div>
                        <div class="form-group">
                            <label for="pages">Pages</label>
                            <input type="number" name="pages" id="pages" value="{{ old('pages', $book->pages) }}" min="1">
                        </div>
                        <div class="form-group">
                            <label for="published_year">Published Year</label>
                            <input type="number" name="published_year" id="published_year" value="{{ old('published_year', $book->published_year) }}" min="1000" max="2100">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" class="resize-none">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Cover Image</label>
                        <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
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
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl mb-6">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_featured" class="ml-3 block text-sm font-bold text-gray-700">
                            Mark as featured book
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
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
            const pdfContent = document.getElementById('pdf-content');
            if (pdfContent) {
                pdfContent.innerHTML = `
                    <div class="text-center">
                        <svg class="mx-auto h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">${file.name}</p>
                        <p class="text-xs text-gray-500">Click to change</p>
                    </div>
                `;
            }
            const pdfDropzone = document.getElementById('pdf-dropzone');
            if (pdfDropzone) {
                pdfDropzone.classList.remove('border-gray-300');
                pdfDropzone.classList.add('border-green-500');
            }
        }
    }
</script>
@endpush
