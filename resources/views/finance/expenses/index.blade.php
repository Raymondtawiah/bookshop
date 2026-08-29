@extends('layouts.finance')

@section('title', 'Expenses')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Expenses</h1>
        <p class="text-gray-600">Record and manage business expenses</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-6 border-b border-gray-100">
            <form method="GET" action="{{ route('finance.expenses') }}" class="flex flex-col gap-4" id="expenseFilterForm">
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="text" name="search" placeholder="Search expenses by name or category..."
                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        value="{{ request('search') }}">
                    <input type="text" name="paid_to" placeholder="Filter by person/vendor..."
                        class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        value="{{ request('paid_to') }}">
                    <select name="category" id="categoryFilter" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <a href="{{ route('finance.expenses') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm">Reset</a>
                        <a href="{{ route('finance.expenses.download', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm">Download Word</a>
                    </div>
                </div>
            </form>
        </div>

        @if($filteredTotal > 0 || request()->hasAny(['search', 'category', 'paid_to', 'start_date', 'end_date']))
            <div class="px-6 py-3 bg-indigo-50 border-t border-gray-100">
                <p class="text-sm text-indigo-700">
                    <span class="font-semibold">Total Filtered:</span> ${{ number_format($filteredTotal, 2) }}
                    @if(request()->hasAny(['search', 'category', 'paid_to', 'start_date', 'end_date']))
                        <span class="text-indigo-500">(filtered results)</span>
                    @endif
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Paid To</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Receipt</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $expense->expense_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $expense->paid_to ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $expense->category }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-red-600">${{ number_format($expense->amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $expense->date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @if($expense->receipt_path)
                                    <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($canEdit)
                                    <div class="flex gap-2">
                                        <button onclick="editExpense({{ $expense }})" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</button>
                                        <form method="POST" action="{{ route('finance.expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Read only</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $expenses->links() }}
        </div>
    </div>

    @if($canEdit)
    <!-- Add/Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4" id="expenseFormTitle">Add New Expense</h2>
        <form method="POST" action="{{ route('finance.expenses.store') }}" id="expenseForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="expenseFormMethod" value="POST">
            <input type="hidden" name="expense_id" id="expenseId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expense Name</label>
                    <input type="text" name="expense_name" id="expense_name" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paid To (Person/Vendor)</label>
                    <input type="text" name="paid_to" id="expense_paid_to" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Who received the payment?">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="category" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <input type="number" step="0.01" name="amount" id="expense_amount" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" id="expense_date" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="expense_notes" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Receipt (optional)</label>
                    <input type="file" name="receipt" id="receipt" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500" accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>

            <div class="mt-4 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Save</button>
                <button type="button" onclick="resetExpenseForm()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Reset</button>
            </div>
        </form>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    function editExpense(expense) {
        document.getElementById('expenseFormTitle').textContent = 'Edit Expense';
        document.getElementById('expenseFormMethod').value = 'PUT';
        document.getElementById('expenseForm').action = '/finance/expenses/' + expense.id;
        document.getElementById('expenseId').value = expense.id;
        document.getElementById('expense_name').value = expense.expense_name;
        document.getElementById('expense_paid_to').value = expense.paid_to ?? '';
        document.getElementById('category').value = expense.category;
        document.getElementById('expense_amount').value = expense.amount;
        document.getElementById('expense_date').value = expense.date;
        document.getElementById('expense_notes').value = expense.notes || '';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function resetExpenseForm() {
        document.getElementById('expenseFormTitle').textContent = 'Add New Expense';
        document.getElementById('expenseFormMethod').value = 'POST';
        document.getElementById('expenseForm').action = '{{ route('finance.expenses.store') }}';
        document.getElementById('expenseId').value = '';
        document.getElementById('expense_name').value = '';
        document.getElementById('expense_paid_to').value = '';
        document.getElementById('category').value = '';
        document.getElementById('expense_amount').value = '';
        document.getElementById('expense_date').value = '';
        document.getElementById('expense_notes').value = '';
        document.getElementById('receipt').value = '';
    }

    (function() {
        const form = document.getElementById('expenseFilterForm');
        if (!form) return;
        const searchInput = form.querySelector('input[name="search"]');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    form.submit();
                }, 600);
            });
        }
        const autoFields = form.querySelectorAll('select[name="category"], input[name="paid_to"], input[name="start_date"], input[name="end_date"]');
        autoFields.forEach(function(field) {
            field.addEventListener('change', function() {
                form.submit();
            });
        });
    })();
</script>
@endpush
