@extends('layouts.finance')

@section('title', 'Payments')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Payments</h1>
        <p class="text-gray-600">Transaction history across all channels</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <form method="GET" action="{{ route('finance.payments') }}" class="flex flex-col sm:flex-row gap-4">
                <select name="type" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="orders" {{ $type === 'orders' ? 'selected' : '' }}>Orders</option>
                    <option value="webinars" {{ $type === 'webinars' ? 'selected' : '' }}>Webinars</option>
                    <option value="coaching" {{ $type === 'coaching' ? 'selected' : '' }}>Coaching</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $payment['type'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $payment['reference'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $payment['customer'] }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $payment['currency'] === 'GHS' ? '₵' : '$' }}{{ number_format($payment['amount'], 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Paid</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($payment['date'])->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
