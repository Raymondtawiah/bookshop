@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.orders') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
                <h2 class="text-lg sm:text-xl font-semibold text-white">Order #{{ $order->order_number ?? $order->id }}</h2>
                <span class="mt-2 sm:mt-0 px-2 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap
                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($order->payment_status ?? 'pending') }}
                </span>
            </div>
        </div>

        <div class="px-4 py-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Customer Information</h3>
                    <dl class="space-y-2 sm:space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="text-gray-900">{{ $order->customer_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Contact</dt>
                            <dd class="text-gray-900">{{ $order->contact ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nationality</dt>
                            <dd class="text-gray-900">{{ $order->nationality ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="text-gray-900">{{ $order->residence ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                    <dl class="space-y-3">
<div>
                             <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                             <dd class="text-gray-900">
                                 @if($order->payment_method === 'paystack')
                                     Paystack
                                 @elseif($order->payment_method === 'momo')
                                     Mobile Money
                                 @elseif($order->payment_method === 'bank')
                                     Bank Transfer
                                 @else
                                     Card Payment
                                 @endif
                             </dd>
                         </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                            <dd class="text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
<div>
                             <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                             <dd class="text-xl font-bold text-indigo-600">${{ number_format($order->total_amount_usd ?? $order->total_amount, 2) }}</dd>
                         </div>
                        @if($order->discount_code)
                        <div class="pt-3 border-t border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Discount Applied</dt>
                            <dd class="text-sm text-green-600 font-medium">{{ $order->discount_code }}</dd>
                            <dd class="text-xs text-gray-500">-${{ number_format($order->discount_amount ?? 0, 2) }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Book Offer Status</h3>
                <dl class="space-y-3">
                    @if($order->book_offered)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Book Offered</dt>
                        <dd class="text-green-600 font-medium">Yes</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Offered At</dt>
                        <dd class="text-gray-900">{{ $order->book_offered_at?->format('M j, Y g:i A') ?? 'N/A' }}</dd>
                    </div>
                    @else
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Book Offered</dt>
                        <dd class="text-gray-500">No</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Send Book Offer to Customer</h3>
                <div class="bg-gray-50 rounded-lg p-4 sm:p-6">
                    <form action="{{ route('admin.orders.sendBookOffer', $order->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-1">PDF File (optional)</label>
                            <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="override_email" class="block text-sm font-medium text-gray-700 mb-1">Email (optional - override order email)</label>
                            <input type="email" name="override_email" id="override_email"
                                placeholder="Leave empty to use {{ $order->user?->email ?? $order->email ?? 'no email' }}"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-semibold" @if($order->book_offered) disabled @endif>
                            {{ $order->book_offered ? 'Entered' : 'Enter' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ordered Books</h3>
                @php
                    $items = $order->order_items;
                    $itemsTotal = 0;
                    foreach($items as $item) {
                        $price = is_array($item) ? ($item['unit_price_usd'] ?? ($item['unit_price'] ?? 0)) : ($item->unit_price_usd ?? ($item->unit_price ?? 0));
                        $qty = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
                        $itemsTotal += $price * $qty;
                    }
                @endphp
                @if(!empty($items) && $items->count() > 0)
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Book</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Price</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Qty</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($items as $item)
                                <tr>
                                    <td class="px-2 py-3">
                                        @php
                                            $name = is_array($item) ? ($item['product_name'] ?? 'Unknown') : ($item->product_name ?? 'Unknown');
                                            $bookId = is_array($item) ? ($item['book_id'] ?? null) : ($item->book_id ?? null);
                                            $price = is_array($item) ? ($item['unit_price_usd'] ?? ($item['unit_price'] ?? 0)) : ($item->unit_price_usd ?? ($item->unit_price ?? 0));
                                            $qty = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
                                            $total = is_array($item) ? ($item['total_price_usd'] ?? ($price * $qty)) : ($item->total_price_usd ?? ($price * $qty));
                                        @endphp
                                        <div class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($name, 40) }}</div>
                                        @if($bookId)
                                            <div class="text-xs text-gray-500">Book ID: {{ $bookId }}</div>
                                        @endif
                                    </td>
                                    <td class="px-2 py-3 text-sm text-gray-900 whitespace-nowrap">${{ number_format($price, 2) }}</td>
                                    <td class="px-2 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $qty }}</td>
                                    <td class="px-2 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">${{ number_format($total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100">
                                @if($order->discount_code)
                                <tr>
                                    <td colspan="3" class="px-2 py-2 text-sm font-medium text-green-600 text-right">Discount ({{ $order->discount_code }}):</td>
                                    <td class="px-2 py-2 text-sm font-medium text-green-600">-${{ number_format($order->discount_amount ?? 0, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="px-2 py-3 text-sm font-bold text-gray-700 text-right">Final Total:</td>
                                    <td class="px-2 py-3 text-sm font-bold text-indigo-600">${{ number_format($order->total_amount_usd ?? $itemsTotal, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @else
                <p class="text-gray-500 text-sm">No order items found.</p>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order Status</h3>
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                        <select name="status" id="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection