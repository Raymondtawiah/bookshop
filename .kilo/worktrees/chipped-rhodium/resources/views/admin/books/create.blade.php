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
</head>

<body class="bg-gray-50 font-sans">

<x-admin-navbar />

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add New Book</h1>
            <p class="text-gray-500">Upload a new book to your inventory</p>
        </div>

        <a href="{{ route('admin.books') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white border hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <!-- TOGGLE -->
    <div class="bg-white border rounded-xl p-2 flex gap-2 w-fit mb-8 shadow-sm">
        <button type="button"
                onclick="switchType('cover')"
                id="btn-cover"
                class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium">
            📘 Book Cover
        </button>

        <button type="button"
                onclick="switchType('pdf')"
                id="btn-pdf"
                class="px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-100">
            📄 PDF Book
        </button>
    </div>

    <!-- ================= COVER FORM ================= -->
    <section id="cover-form" class="bg-white rounded-2xl border shadow-sm p-8">

        <h2 class="text-xl font-semibold mb-6 text-gray-800">Book Cover Details</h2>

        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="book_type" value="cover">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg text-sm">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-5">

                <input type="text" name="title" value="{{ old('title') }}" placeholder="Title" required
                       class="input">

                <input type="text" name="author" value="{{ old('author') }}" placeholder="Author" required
                       class="input">

                <input type="number" name="price" value="{{ old('price') }}" placeholder="Price" required
                       class="input">

                <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="ISBN"
                       class="input">

                <input type="number" name="pages" value="{{ old('pages') }}" placeholder="Pages"
                       class="input">

                <input type="number" name="published_year" value="{{ old('published_year') }}" placeholder="Year"
                       class="input">

            </div>

            <textarea name="description" placeholder="Description"
                      class="input w-full h-28">{{ old('description') }}</textarea>

            <!-- COVER -->
            <div>
                <label class="text-sm font-medium text-gray-700">Cover Image *</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="mt-2 block w-full border rounded-lg p-2">
            </div>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1"
                       class="rounded border-gray-300 text-indigo-600">
                <span class="text-sm">Mark as featured</span>
            </label>

            <div class="flex justify-end">
                <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Save Book
                </button>
            </div>
        </form>
    </section>

    <!-- ================= PDF FORM ================= -->
    <section id="pdf-form" class="hidden bg-white rounded-2xl border shadow-sm p-8">

        <h2 class="text-xl font-semibold mb-6 text-gray-800">PDF Book Details</h2>

        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="book_type" value="pdf">
            <input type="hidden" name="is_free" value="1">
            <input type="hidden" name="price" value="0">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg text-sm">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-5">

                <input type="text" name="title" value="{{ old('title') }}" placeholder="Title" required class="input">
                <input type="text" name="author" value="{{ old('author') }}" placeholder="Author" required class="input">

            </div>

            <textarea name="description" placeholder="Description"
                      class="input w-full h-28">{{ old('description') }}</textarea>

            <!-- PDF FILE -->
            <div>
                <label class="text-sm font-medium text-gray-700">PDF File *</label>
                <input type="file" name="book_pdf" accept="application/pdf"
                       class="mt-2 block w-full border rounded-lg p-2" required>
            </div>

            <!-- COVER OPTIONAL -->
            <div>
                <label class="text-sm font-medium text-gray-700">Cover Image</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="mt-2 block w-full border rounded-lg p-2">
            </div>

            <div class="flex justify-end">
                <button class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Upload PDF Book
                </button>
            </div>
        </form>
    </section>

</main>

<!-- STYLE -->
<style>
    .input {
        @apply w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none;
    }
</style>

<!-- SCRIPT -->
<script>
function switchType(type) {
    const cover = document.getElementById('cover-form');
    const pdf = document.getElementById('pdf-form');

    const btnCover = document.getElementById('btn-cover');
    const btnPdf = document.getElementById('btn-pdf');

    if (type === 'cover') {
        cover.classList.remove('hidden');
        pdf.classList.add('hidden');

        btnCover.classList.add('bg-indigo-600', 'text-white');
        btnPdf.classList.remove('bg-indigo-600', 'text-white');

    } else {
        pdf.classList.remove('hidden');
        cover.classList.add('hidden');

        btnPdf.classList.add('bg-indigo-600', 'text-white');
        btnCover.classList.remove('bg-indigo-600', 'text-white');
    }
}
</script>

</body>
</html>