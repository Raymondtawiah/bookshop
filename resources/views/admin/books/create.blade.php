<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add New Book - {{ config('app.name', 'Bookshop') }}</title>
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
    </style>
</head>

<body class="bg-gray-50 font-sans pt-20">
<x-admin-navbar />

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Add New Book</h1>
                <p class="text-gray-500 font-medium">Upload a new book to your inventory</p>
            </div>
        </div>

        <a href="{{ route('admin.books') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <!-- TOGGLE -->
    <div class="bg-white border border-gray-200 rounded-2xl p-2 flex gap-2 w-fit mb-8 shadow-sm">
        <button type="button"
                onclick="switchType('cover')"
                id="btn-cover"
                class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-md">
            Book Cover
        </button>

        <button type="button"
                onclick="switchType('pdf')"
                id="btn-pdf"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition-colors">
            PDF Book
        </button>
    </div>

    <!-- ================= COVER FORM ================= -->
    <section id="cover-form" class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-8">

        <h2 class="text-xl font-bold text-gray-900 mb-6">Book Cover Details</h2>

        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="book_type" value="cover">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-sm font-medium">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter book title" required class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Author *</label>
                    <input type="text" name="author" value="{{ old('author') }}" placeholder="Enter author name" required class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Price ($) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="0.00" required class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="978-..." class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pages</label>
                    <input type="number" name="pages" value="{{ old('pages') }}" placeholder="Number of pages" class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Published Year</label>
                    <input type="number" name="published_year" value="{{ old('published_year') }}" placeholder="e.g. 2024" class="input-field">
                </div>

            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea name="description" placeholder="Enter book description..." class="input-field h-28 resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <label class="block text-sm font-bold text-gray-700 mb-3">Cover Image *</label>
                <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-colors">
                <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm font-bold text-gray-700">Mark as featured book</span>
            </label>

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Book
                </button>
            </div>
        </form>
    </section>

    <!-- ================= PDF FORM ================= -->
    <section id="pdf-form" class="hidden bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-8">

        <h2 class="text-xl font-bold text-gray-900 mb-6">PDF Book Details</h2>

        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="book_type" value="pdf">
            <input type="hidden" name="is_free" value="1">
            <input type="hidden" name="price" value="0">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-sm font-medium">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter book title" required class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Author *</label>
                    <input type="text" name="author" value="{{ old('author') }}" placeholder="Enter author name" required class="input-field">
                </div>

            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea name="description" placeholder="Enter book description..." class="input-field h-28 resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <label class="block text-sm font-bold text-gray-700 mb-3">PDF File *</label>
                <input type="file" name="book_pdf" accept="application/pdf" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
            </div>

            <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <label class="block text-sm font-bold text-gray-700 mb-3">Cover Image (Optional)</label>
                <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Upload PDF Book
                </button>
            </div>
        </form>
    </section>

</main>

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

</body>
</html>