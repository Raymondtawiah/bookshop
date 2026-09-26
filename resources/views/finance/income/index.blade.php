@extends('layouts.finance')

@section('title', 'Income')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Income</h1>
                <p class="page-subtitle">Track money received from various sources</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="panel mb-6">
            <div class="panel-header">
                <form method="GET" action="{{ route('finance.income') }}" class="flex flex-col sm:flex-row gap-4 flex-1">
                    <input type="text" name="search" placeholder="Search by source or client..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ request('search') }}">
                    <div class="select-wrapper">
                        <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        <span class="select-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </span>
                    </div>
                </form>
            </div>
            <div class="weekend-body">
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
        </div>

        <div class="card">
            <h2 class="card-title" id="formTitle">Add New Income</h2>
            <form method="POST" action="{{ route('finance.income.store') }}" id="incomeForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="income_id" id="incomeId">

                <div class="two-fields">
                    <div class="form-group">
                        <label>Source</label>
                        <div class="select-wrapper">
                            <select name="source" id="source" required>
                                <option value="">Select source</option>
                                <option value="Consulting">Consulting</option>
                                <option value="Course">Course</option>
                                <option value="Sponsorship">Sponsorship</option>
                                <option value="Other">Other</option>
                            </select>
                            <span class="select-arrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Customer/Client Name</label>
                        <input type="text" name="customer_client_name" id="customer_client_name" required>
                    </div>
                </div>
                <div class="two-fields">
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" required>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="date" required>
                    </div>
                </div>
                <div class="two-fields">
                    <div class="form-group">
                        <label>Payment Status</label>
                        <div class="select-wrapper">
                            <select name="payment_status" id="payment_status" required>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                            <span class="select-arrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" id="notes" rows="3"></textarea>
                    </div>
                </div>

                <div class="flex gap-3" style="justify-content: flex-end; padding: 20px 0 25px; border-bottom: 1px solid #e5e7ed;">
                    <button type="button" onclick="resetForm()" class="save">Reset</button>
                    <button type="submit" class="publish" style="width: auto; padding: 0 30px;">Save</button>
                </div>
            </form>
        </div>
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
