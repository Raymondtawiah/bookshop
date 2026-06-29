@extends('layouts.finance')

@section('title', 'Expenses - Finance')

@section('content')
    <!-- Finance Sidebar -->
    <nav class="flex space-x-4 mb-6 overflow-x-auto">
        <a href="{{ route('finance.dashboard') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.dashboard') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
        <a href="{{ route('finance.incomes') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.incomes*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Income</a>
        <a href="{{ route('finance.expenses') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.expenses*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Expenses</a>
        <a href="{{ route('finance.payments') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.payments*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Payments</a>
        <a href="{{ route('finance.reports') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.reports*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Reports</a>
        <a href="{{ route('finance.settings') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.settings*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Settings</a>
    </nav>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Expense Records</h1>
        <button onclick="openExpenseModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            Add Expense
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full" id="expenses-table">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Expense Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="expenses-tbody">
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<div id="expense-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4" id="expense-modal-title">Add Expense</h3>
        <form id="expense-form" onsubmit="saveExpense(event)" enctype="multipart/form-data">
            <input type="hidden" id="expense-id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Expense Name</label>
                <input type="text" id="expense-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                    <option value="">Select Category</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Development">Development</option>
                    <option value="Software">Software</option>
                    <option value="Team payments">Team payments</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" id="expense-amount" required step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="expense-date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="expense-notes" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" rows="3"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Receipt (optional)</label>
                <input type="file" id="receipt" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">Save</button>
                <button type="button" onclick="closeExpenseModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadExpenses() {
        const response = await fetch('{{ route("api.finance.expenses") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        const tbody = document.getElementById('expenses-tbody');
        if (data.success && data.data.length > 0) {
            tbody.innerHTML = data.data.map(expense => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">${expense.expense_name}</td>
                    <td class="px-6 py-4">${expense.category}</td>
                    <td class="px-6 py-4">$${parseFloat(expense.amount).toFixed(2)}</td>
                    <td class="px-6 py-4">${expense.date}</td>
                    <td class="px-6 py-4">
                        ${expense.receipt_path ? `<a href="/storage/${expense.receipt_path}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">View</a>` : '-'}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editExpense(${expense.id})" class="text-emerald-600 hover:text-emerald-800 text-sm mr-2">Edit</button>
                        <button onclick="deleteExpense(${expense.id})" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No expense records found</td></tr>';
        }
    }

    function openExpenseModal() {
        document.getElementById('expense-modal').classList.remove('hidden');
        document.getElementById('expense-modal-title').textContent = 'Add Expense';
        document.getElementById('expense-form').reset();
        document.getElementById('expense-id').value = '';
    }

    function closeExpenseModal() {
        document.getElementById('expense-modal').classList.add('hidden');
    }

    async function saveExpense(e) {
        e.preventDefault();
        const id = document.getElementById('expense-id').value;
        const formData = new FormData();
        formData.append('expense_name', document.getElementById('expense-name').value);
        formData.append('category', document.getElementById('category').value);
        formData.append('amount', document.getElementById('expense-amount').value);
        formData.append('date', document.getElementById('expense-date').value);
        formData.append('notes', document.getElementById('expense-notes').value);
        if (document.getElementById('receipt').files[0]) {
            formData.append('receipt', document.getElementById('receipt').files[0]);
        }

        let url, method;
        if (id) {
            url = '{{ route("api.finance.expenses.update", ":id") }}'.replace(':id', id);
            method = 'POST';
            formData.append('_method', 'PUT');
        } else {
            url = '{{ route("api.finance.expenses.store") }}';
            method = 'POST';
        }

        await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData,
        });
        closeExpenseModal();
        loadExpenses();
    }

    async function editExpense(id) {
        const response = await fetch('{{ route("api.finance.expenses.show", ":id") }}'.replace(':id', id), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            document.getElementById('expense-id').value = data.data.id;
            document.getElementById('expense-name').value = data.data.expense_name;
            document.getElementById('category').value = data.data.category;
            document.getElementById('expense-amount').value = data.data.amount;
            document.getElementById('expense-date').value = data.data.date;
            document.getElementById('expense-notes').value = data.data.notes || '';
            document.getElementById('expense-modal-title').textContent = 'Edit Expense';
            openExpenseModal();
        }
    }

    async function deleteExpense(id) {
        if (!confirm('Are you sure you want to delete this expense record?')) return;
        await fetch('{{ route("api.finance.expenses.destroy", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        });
        loadExpenses();
    }

    loadExpenses();
</script>
@endpush