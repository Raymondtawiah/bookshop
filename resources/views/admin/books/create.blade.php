@extends('layouts.admin')

@section('title', 'Add New Book')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Add New Book</h1>
                <p class="page-subtitle">Upload a new book to your inventory</p>
            </div>
            <a href="{{ route('admin.books') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>

        <!-- TOGGLE -->
        <div class="panel mb-6">
            <div class="panel-body">
                <div class="bg-white border border-gray-200 rounded-2xl p-2 flex gap-2 w-fit shadow-sm">
                    <button type="button" onclick="switchType('cover')" id="btn-cover" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-md">
                        Book Cover
                    </button>
                    <button type="button" onclick="switchType('pdf')" id="btn-pdf" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                        PDF Book
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= COVER FORM ================= -->
        <section id="cover-form" class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Book Cover Details</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
                    @csrf
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
                            <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Enter book title" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author *</label>
                            <input type="text" name="author" id="author" value="{{ old('author') }}" placeholder="Enter author name" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" placeholder="978-...">
                        </div>
                        <div class="form-group">
                            <label for="pages">Pages</label>
                            <input type="number" name="pages" id="pages" value="{{ old('pages') }}" placeholder="Number of pages">
                        </div>
                        <div class="form-group">
                            <label for="published_year">Published Year</label>
                            <input type="number" name="published_year" id="published_year" value="{{ old('published_year') }}" placeholder="e.g. 2024">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="Enter book description..." class="resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Cover Image *</label>
                        <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl mb-6">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_featured" class="ml-3 block text-sm font-bold text-gray-700">
                            Mark as featured book
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Book
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- ================= PDF FORM ================= -->
        <section id="pdf-form" class="panel hidden">
            <div class="panel-header">
                <h2 class="panel-title">PDF Book Details</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
                    @csrf
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
                            <label for="pdf-title">Title *</label>
                            <input type="text" name="title" id="pdf-title" value="{{ old('title') }}" placeholder="Enter book title" required>
                        </div>
                        <div class="form-group">
                            <label for="pdf-author">Author *</label>
                            <input type="text" name="author" id="pdf-author" value="{{ old('author') }}" placeholder="Enter author name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pdf-description">Description</label>
                        <textarea name="description" id="pdf-description" rows="3" placeholder="Enter book description..." class="resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>PDF File *</label>
                        <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <input type="file" name="book_pdf" accept="application/pdf" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Cover Image (Optional)</label>
                        <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Upload PDF Book
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    function switchType(type) {
        const cover = document.getElementById('cover-form');
        const pdf = document.getElementById('pdf-form');
        const btnCover = document.getElementById('btn-cover');
        const btnPdf = document.getElementById('btn-pdf');

        if (type === 'cover') {
            cover.classList.remove('hidden');
            pdf.classList.add('hidden');
            btnCover.className = 'px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-md';
            btnPdf.className = 'px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition-colors';
        } else {
            pdf.classList.remove('hidden');
            cover.classList.add('hidden');
            btnPdf.className = 'px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-md';
            btnCover.className = 'px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition-colors';
        }
    }
</script>
@endpush
