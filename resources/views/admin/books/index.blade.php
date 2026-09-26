@extends('layouts.admin')

@section('title', $book ? $book->title : 'Books - Visa with Nathaniel')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Books</h1>
                <p class="page-subtitle">Manage your books, pricing and bonus sessions.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.books.edit', $book->id) }}" class="p-2.5 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-gray-200" title="Edit book">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <a href="{{ route('admin.books.create') }}" class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors shadow-sm" title="Add book">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </a>
            </div>
        </div>

        @if($book)
        <!-- TABS -->
        <div class="tabs">
            <div class="tab active" data-tab="details" onclick="switchTab('details')">Book details</div>
            <div class="tab" data-tab="orders" onclick="switchTab('orders')">Orders</div>
            <div class="tab" data-tab="bonus" onclick="switchTab('bonus')">Bonus sessions</div>
        </div>

        <!-- TAB CONTENT -->
        <div class="cards">
            <!-- BOOK DETAILS TAB -->
            <div id="tab-details" class="tab-content">
                <div class="card">
                    <h2 class="card-title">Book details</h2>
                    <div class="book-layout">
                        <div>
                            <div class="book-cover">
                                <div class="cover-title">
                                    {{ strtoupper($book->title) }}
                                </div>
                                <div class="cover-line"></div>
                                <div class="cover-small">
                                    {{ strtoupper($book->author) }}
                                </div>
                                <div class="cover-author">{{ strtoupper($book->author) }}</div>
                            </div>
                            @if($book->cover_image)
                            <button class="change-cover">Change cover</button>
                            @endif
                        </div>
                        <div>
                            <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" value="{{ $book->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Author</label>
                                    <input type="text" name="author" value="{{ $book->author }}" required>
                                </div>
                                <div class="two-fields">
                                    <div class="form-group">
                                        <label>Price (USD)</label>
                                        <input type="text" name="price" value="{{ number_format($book->price, 2) }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Format</label>
                                        <div class="select-wrapper">
                                            <select name="format">
                                                @if($book->is_free && $book->book_pdf)
                                                    <option>Digital PDF</option>
                                                @else
                                                    <option>Physical Book</option>
                                                    <option>Digital PDF</option>
                                                @endif
                                            </select>
                                            <span class="select-arrow">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 9l6 6 6-6"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Book file (PDF)</label>
                                    <div class="file-box">
                                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M6 2h8l4 4v16H6z"></path>
                                            <path d="M14 2v5h5"></path>
                                            <path d="M9 13h6"></path>
                                            <path d="M9 17h6"></path>
                                        </svg>
                                        <span class="file-name">{{ $book->book_pdf ?: 'No file uploaded' }}</span>
                                        @if($book->book_pdf)
                                        <span class="replace">Replace file</span>
                                        @endif
                                    </div>
                                    <input type="file" name="book_pdf" accept=".pdf" class="mt-2">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description">{{ $book->description ?: 'No description provided.' }}</textarea>
                                    <div class="description-count">{{ strlen($book->description ?? '') }}/500</div>
                                </div>
                                <button type="submit" class="publish" style="width: auto; padding: 0 30px;">Update Book</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ORDERS TAB -->
            <div id="tab-orders" class="tab-content" style="display: none;">
                <div class="card">
                    <h2 class="card-title">Orders</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order #</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Payment</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $order->order_number ?? $order->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->customer_name ?? 'Guest' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                        @if($order->currency === 'GHS')
                                            ₵{{ number_format($order->total_amount, 2) }}
                                        @else
                                            ${{ number_format($order->total_amount, 2) }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($order->status === 'confirmed')
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Confirmed</span>
                                        @elseif($order->status === 'processing')
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">Processing</span>
                                        @elseif($order->status === 'shipped')
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700">Shipped</span>
                                        @elseif($order->status === 'delivered')
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-teal-100 text-teal-700">Delivered</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Cancelled</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($order->payment_status === 'paid')
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Paid</span>
                                        @elseif($order->payment_status === 'pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Failed</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                             <button type="button" onclick="openOrderDetailsModal({{ $order->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View order details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                            @if($order->payment_status === 'pending')
                                                <button type="button" onclick="openReminderModal({{ $order->id }}, '{{ $order->customer_name ?? 'customer' }}')" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Send payment reminder">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                    </svg>
                                                </button>
                                            @endif
                                            @if($order->payment_status === 'paid')
                                                <button type="button" onclick="openBookModal({{ $order->id }}, '{{ $order->customer_name ?? 'customer' }}')" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Send book">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                </button>
                                            @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No orders yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BONUS SESSIONS TAB -->
            <div id="tab-bonus" class="tab-content" style="display: none;">
                <div class="card">
                    <h2 class="card-title">Complimentary session</h2>
                    <p class="bonus-subtitle">Offer a bonus session to book buyers.</p>
                    <div class="bonus-header" style="margin-top: 20px;">
                        <div class="enabled">
                            <div class="toggle">
                                <div class="toggle-circle"></div>
                            </div>
                            Enabled
                        </div>
                    </div>
                    <div class="bonus-fields" style="margin-top: 20px;">
                        <div class="form-group">
                            <label>Session name</label>
                            <input type="text" value="{{ $book->title }} Q&amp;A">
                        </div>
                        <div class="form-group">
                            <label>Duration</label>
                            <div class="select-wrapper">
                                <select>
                                    <option>10 minutes</option>
                                    <option>15 minutes</option>
                                    <option>30 minutes</option>
                                    <option>60 minutes</option>
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M5 12l4 4L19 6"></path>
                            </svg>
                        </div>
                        <span class="checkbox-text">One session per buyer</span>
                    </div>
                    <div class="form-group">
                        <label class="bonus-label">Availability</label>
                        <div class="select-wrapper">
                            <select>
                                <option>Designated weekend slots</option>
                                <option>Any available slot</option>
                            </select>
                            <span class="select-arrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <button class="manage">Manage bonus slots</button>
                    <div class="divider"></div>
                    <p class="booking-note">Booking link appears only after successful purchase.</p>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="stats">
            <div class="stat">
                <div class="stat-number">{{ $orders->where('payment_status', 'paid')->count() }}</div>
                <div class="stat-label">paid orders</div>
            </div>
            <div class="stat">
                <div class="stat-number">0</div>
                <div class="stat-label">bonus sessions booked</div>
            </div>
            <div class="stat">
                <div class="stat-number">0</div>
                <div class="stat-label">bonus sessions completed</div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Design concept • Sample data
        </div>
        @else
        <div class="p-16 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 18 16.5 18c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0 3.332.477 4.5 1.253"/>
                </svg>
            </div>
            <p class="text-gray-500 mb-4 text-lg font-medium">No books yet. Add your first book!</p>
            <a href="{{ route('admin.books.create') }}" class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors shadow-sm inline-flex items-center justify-center" title="Add book">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </a>
        </div>
        @endif
    </div>

    <!-- Order Details Modal -->
    <div id="order-details-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Order Details</h3>
                <button type="button" onclick="closeOrderDetailsModal()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="order-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Order Number</span>
                        <span class="detail-value" id="detail-order-number"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Customer</span>
                        <span class="detail-value" id="detail-customer-name"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Total Amount</span>
                        <span class="detail-value" id="detail-total-amount"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="detail-value" id="detail-status"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Payment Status</span>
                        <span class="detail-value" id="detail-payment-status"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date</span>
                        <span class="detail-value" id="detail-date"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeOrderDetailsModal()" class="modal-btn-cancel">Close</button>
            </div>
        </div>
    </div>

    <!-- Payment Reminder Modal -->
    <div id="reminder-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Send Payment Reminder</h3>
                <button type="button" onclick="closeReminderModal()" class="modal-close">&times;</button>
            </div>
            <form id="reminder-form" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="mb-4 text-sm text-gray-600">Send a payment reminder to <strong id="reminder-customer-name"></strong></p>
                    <div class="form-group">
                        <label>Custom Message (optional)</label>
                        <textarea name="custom_message" rows="4" class="modal-textarea" placeholder="Enter a custom message to include in the reminder email..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeReminderModal()" class="modal-btn-cancel">Cancel</button>
                    <button type="submit" class="modal-btn-submit">Send Reminder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Send Book Modal -->
    <div id="book-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Send Book to Customer</h3>
                <button type="button" onclick="closeBookModal()" class="modal-close">&times;</button>
            </div>
            <form id="book-form" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="mb-4 text-sm text-gray-600">Send the book PDF to <strong id="book-customer-name"></strong></p>
                    <div class="form-group">
                        <label>Upload PDF File</label>
                        <input type="file" name="pdf_file" accept=".pdf" required class="modal-file-input">
                        <p class="text-xs text-gray-500 mt-1">Only PDF files are allowed (max 50MB)</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeBookModal()" class="modal-btn-cancel">Cancel</button>
                    <button type="submit" class="modal-btn-submit">Send Book</button>
                </div>
            </form>
        </div>
    </div>

    @endsection

    @push('scripts')
    <script>
        const orders = @json($orders);

        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById('tab-' + tabName).style.display = 'block';
            
            // Add active class to selected tab
            document.querySelectorAll('.tab').forEach(tab => {
                if (tab.dataset.tab === tabName) {
                    tab.classList.add('active');
                }
            });
        }

        // Payment Reminder Modal
        function openReminderModal(orderId, customerName) {
            document.getElementById('reminder-customer-name').textContent = customerName;
            document.getElementById('reminder-form').action = '/admin/orders/' + orderId + '/send-payment-reminder';
            document.getElementById('reminder-modal').style.display = 'flex';
        }

        function closeReminderModal() {
            document.getElementById('reminder-modal').style.display = 'none';
            document.getElementById('reminder-form').reset();
        }

        // Send Book Modal
        function openBookModal(orderId, customerName) {
            document.getElementById('book-customer-name').textContent = customerName;
            document.getElementById('book-form').action = '/admin/orders/' + orderId + '/send-book-pdf';
            document.getElementById('book-modal').style.display = 'flex';
        }

        function closeBookModal() {
            document.getElementById('book-modal').style.display = 'none';
            document.getElementById('book-form').reset();
        }

        // Order Details Modal
        function openOrderDetailsModal(orderId) {
            const order = orders.find(o => o.id == orderId);
            if (!order) return;
            document.getElementById('detail-order-number').textContent = '#' + (order.order_number || order.id);
            document.getElementById('detail-customer-name').textContent = order.customer_name || 'Guest';
            document.getElementById('detail-total-amount').textContent = (order.currency === 'GHS' ? '₵' : '$') + parseFloat(order.total_amount).toFixed(2);
            document.getElementById('detail-status').textContent = order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1) : '';
            document.getElementById('detail-payment-status').textContent = order.payment_status ? order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1) : '';
            document.getElementById('detail-date').textContent = order.created_at || '';
            document.getElementById('order-details-modal').style.display = 'flex';
        }

        function closeOrderDetailsModal() {
            document.getElementById('order-details-modal').style.display = 'none';
        }

        // Close modals on overlay click
        window.onclick = function(event) {
            const reminderModal = document.getElementById('reminder-modal');
            const bookModal = document.getElementById('book-modal');
            const orderDetailsModal = document.getElementById('order-details-modal');
            if (event.target === reminderModal) {
                closeReminderModal();
            }
            if (event.target === bookModal) {
                closeBookModal();
            }
            if (event.target === orderDetailsModal) {
                closeOrderDetailsModal();
            }
        }
    </script>

    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            color: #6b7280;
            cursor: pointer;
            line-height: 1;
        }

        .modal-close:hover {
            color: #111827;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 24px;
            border-top: 1px solid #e5e7eb;
        }

        .modal-textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
        }

        .modal-textarea:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .modal-file-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px;
            font-size: 14px;
        }

        .modal-btn-cancel {
            padding: 10px 20px;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .modal-btn-cancel:hover {
            background: #f9fafb;
        }

        .modal-btn-submit {
            padding: 10px 20px;
            border: none;
            background: #4f46e5;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .modal-btn-submit:hover {
            background: #4338ca;
        }

        .order-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .order-details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>


