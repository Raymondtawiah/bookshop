@extends('layouts.finance')

@section('title', 'Expenses')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Expenses</h1>
                <p class="page-subtitle">Record and manage business expenses</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="panel mb-6">
            <div class="panel-header">
                <form method="GET" action="{{ route('finance.expenses') }}" class="flex flex-col sm:flex-row gap-4 flex-1" id="expenseFilterForm">
                    <input type="text" name="search" placeholder="Search expenses by name or category..."
                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        value="{{ request('search') }}">
                    <input type="text" name="paid_to" placeholder="Filter by person/vendor..."
                        class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        value="{{ request('paid_to') }}">
                    <div class="select-wrapper">
                        <select name="category" id="categoryFilter" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                        <span class="select-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </span>
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

            <div class="weekend-body">
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
        </div>

        @if($canEdit)
        <div class="card">
            <h2 class="card-title" id="expenseFormTitle">Add New Expense</h2>
            <form method="POST" action="{{ route('finance.expenses.store') }}" id="expenseForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="expenseFormMethod" value="POST">
                <input type="hidden" name="expense_id" id="expenseId">

                <div class="two-fields">
                    <div class="form-group">
                        <label>Expense Name</label>
                        <input type="text" name="expense_name" id="expense_name" required>
                    </div>
                    <div class="form-group">
                        <label>Paid To (Person/Vendor)</label>
                        <input type="text" name="paid_to" id="expense_paid_to" placeholder="Who received the payment?">
                    </div>
                </div>
                <div class="two-fields">
                    <div class="form-group">
                        <label>Category</label>
                        <div class="select-wrapper">
                            <select name="category" id="category" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            <span class="select-arrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" step="0.01" name="amount" id="expense_amount" required>
                    </div>
                </div>
                <div class="two-fields">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="expense_date" required>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" id="expense_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label>Receipt (optional)</label>
                    <div class="file-box">
                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M6 2h8l4 4v16H6z"></path>
                            <path d="M14 2v5h5"></path>
                            <path d="M9 13h6"></path>
                            <path d="M9 17h6"></path>
                        </svg>
                        <span class="file-name" id="receiptFileName">No file chosen</span>
                        <span class="replace">Replace file</span>
                    </div>
                    <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf" class="mt-2" onchange="document.getElementById('receiptFileName').textContent = this.files[0] ? this.files[0].name : 'No file chosen'">
                </div>

                <div class="flex gap-3" style="justify-content: flex-end; padding: 20px 0 25px; border-bottom: 1px solid #e5e7ed;">
                    <button type="button" onclick="resetExpenseForm()" class="save">Reset</button>
                    <button type="submit" class="publish" style="width: auto; padding: 0 30px;">Save</button>
                </div>
            </form>
        </div>
        @endif
    </div>
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
        document.getElementById('receiptFileName').textContent = 'No file chosen';
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
