<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Books Management - {{ config('app.name', 'Bookshop') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    </head>
    <body class="bg-gray-50 font-sans">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Bookshop Admin</span>
                        </a>
                    </div>

                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.books') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.books*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            Books
                        </a>
                        <a href="{{ route('admin.customers') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.customers*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            Customers
                        </a>
                        <a href="{{ route('admin.orders') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.orders*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            Orders
                        </a>
                        <a href="{{ route('admin.settings') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.settings*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            Settings
                        </a>
                    </nav>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600 transition-colors" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Books Management</h1>
                    <p class="text-gray-500">Manage your book inventory</p>
                </div>
                <a href="{{ route('admin.books.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Book
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Books Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @if($books->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Cover</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Title</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Author</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Category</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Price</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Stock</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">PDF</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($books as $book)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            @if($book->cover_image)
                                                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="h-16 w-12 object-cover rounded">
                                            @else
                                                <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-900">{{ $book->title }}</span>
                                                @if($book->is_featured)
                                                    <span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded">Featured</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $book->author }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $book->category ?? '-' }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">₵{{ number_format($book->price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            @if($book->stock > 0)
                                                <span class="text-green-600">{{ $book->stock }}</span>
                                            @else
                                                <span class="text-red-600">Out of stock</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($book->pdf_file)
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">PDF Ready</span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded">No PDF</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.books.edit', $book->id) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this book?')">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $books->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-4">No books yet. Add your first book!</p>
                        <a href="{{ route('admin.books.create') }}" class="inline-block px-4 py-2 text-indigo-600 hover:text-indigo-700 font-medium">
                            Add New Book →
                        </a>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Bookshop Admin. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>