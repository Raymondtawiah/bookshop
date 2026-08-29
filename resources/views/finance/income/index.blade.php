@extends('layouts.finance')

@section('title', 'Income')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Income</h1>
        <p class="text-gray-600">Track money received from various sources</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row gap-4">
                <form method="GET" action="{{ route('finance.income') }}" class="flex-1">
                    <input type="text" name="search" placeholder="Search by source or client..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ request('search') }}">
                </form>
                <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Source</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($incomes as $income)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $income->source }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $income->customer_client_name }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">${{ number_format($income->amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $income->date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @if($income->payment_status === 'paid')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Paid</span>
                                @elseif($income->payment_status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Failed</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="editIncome({{ $income }})" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</button>
                                    <form method="POST" action="{{ route('finance.income.destroy', $income) }}" onsubmit="return confirm('Delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $incomes->links() }}
        </div>
    </div>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4" id="formTitle">Add New Income</h2>
        <form method="POST" action="{{ route('finance.income.store') }}" id="incomeForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="income_id" id="incomeId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <select name="source" id="source" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">Select source</option>
                        <option value="Consulting">Consulting</option>
                        <option value="Course">Course</option>
                        <option value="Sponsorship">Sponsorship</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer/Client Name</label>
                    <input type="text" name="customer_client_name" id="customer_client_name" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" id="date" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
            </div>

            <div class="mt-4 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Save</button>
                <button type="button" onclick="resetForm()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Reset</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function editIncome(income) {
        document.getElementById('formTitle').textContent = 'Edit Income';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('incomeForm').action = '/finance/income/' + income.id;
        document.getElementById('incomeId').value = income.id;
        document.getElementById('source').value = income.source;
        document.getElementById('customer_client_name').value = income.customer_client_name;
        document.getElementById('amount').value = income.amount;
        document.getElementById('date').value = income.date;
        document.getElementById('payment_status').value = income.payment_status;
        document.getElementById('notes').value = income.notes || '';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('formTitle').textContent = 'Add New Income';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('incomeForm').action = '{{ route('finance.income.store') }}';
        document.getElementById('incomeId').value = '';
        document.getElementById('source').value = '';
        document.getElementById('customer_client_name').value = '';
        document.getElementById('amount').value = '';
        document.getElementById('date').value = '';
        document.getElementById('payment_status').value = 'paid';
        document.getElementById('notes').value = '';
    }
</script>
@endpush
