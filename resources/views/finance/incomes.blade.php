@extends('layouts.finance')

@section('title', 'Income - Finance')

@section('content')
    <!-- Finance Navigation -->
    <nav class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('finance.dashboard') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.dashboard') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
        <a href="{{ route('finance.incomes') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.incomes*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Income</a>
        <a href="{{ route('finance.expenses') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.expenses*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Expenses</a>
        <a href="{{ route('finance.payments') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.payments*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Payments</a>
        <a href="{{ route('finance.reports') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.reports*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Reports</a>
        <a href="{{ route('finance.settings') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('finance.settings*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Settings</a>
    </nav>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Income Records</h1>
        <button onclick="openIncomeModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
            Add Income
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full" id="incomes-table">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="incomes-tbody">
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<div id="income-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4" id="modal-title">Add Income</h3>
        <form id="income-form" onsubmit="saveIncome(event)">
            <input type="hidden" id="income-id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                <select id="source" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select Source</option>
                    <option value="Consulting">Consulting</option>
                    <option value="Course">Course</option>
                    <option value="Sponsorship">Sponsorship</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer/Client Name</label>
                <input type="text" id="customer-client-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" id="amount" required step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select id="payment-status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" rows="3"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700">Save</button>
                <button type="button" onclick="closeIncomeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadIncomes() {
        const response = await fetch('{{ route("finance.incomes-data") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        const data = await response.json();
        const tbody = document.getElementById('incomes-tbody');
        if (data.success && data.data.length > 0) {
            tbody.innerHTML = data.data.map(income => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">${income.source}</td>
                    <td class="px-6 py-4">${income.customer_client_name}</td>
                    <td class="px-6 py-4">$${parseFloat(income.amount).toFixed(2)}</td>
                    <td class="px-6 py-4">${income.date}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full ${income.payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">
                            ${income.payment_status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editIncome(${income.id})" class="text-emerald-600 hover:text-emerald-800 text-sm mr-2">Edit</button>
                        <button onclick="deleteIncome(${income.id})" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No income records found</td></tr>';
        }
    }

    function openIncomeModal() {
        document.getElementById('income-modal').classList.remove('hidden');
        document.getElementById('modal-title').textContent = 'Add Income';
        document.getElementById('income-form').reset();
        document.getElementById('income-id').value = '';
    }

    function closeIncomeModal() {
        document.getElementById('income-modal').classList.add('hidden');
    }

    async function saveIncome(e) {
        e.preventDefault();
        const id = document.getElementById('income-id').value;
        const data = {
            source: document.getElementById('source').value,
            customer_client_name: document.getElementById('customer-client-name').value,
            amount: document.getElementById('amount').value,
            date: document.getElementById('date').value,
            payment_status: document.getElementById('payment-status').value,
            notes: document.getElementById('notes').value,
        };

        let url, method;
        if (id) {
            url = '{{ route("finance.incomes-update", ":id") }}'.replace(':id', id);
            method = 'PUT';
        } else {
            url = '{{ route("finance.incomes-store") }}';
            method = 'POST';
        }

        await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(data),
        });
        closeIncomeModal();
        loadIncomes();
    }

    async function editIncome(id) {
        const response = await fetch('{{ route("finance.incomes-show", ":id") }}'.replace(':id', id), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        const data = await response.json();
        if (data.success) {
            document.getElementById('income-id').value = data.data.id;
            document.getElementById('source').value = data.data.source;
            document.getElementById('customer-client-name').value = data.data.customer_client_name;
            document.getElementById('amount').value = data.data.amount;
            document.getElementById('date').value = data.data.date;
            document.getElementById('payment-status').value = data.data.payment_status;
            document.getElementById('notes').value = data.data.notes || '';
            document.getElementById('modal-title').textContent = 'Edit Income';
            openIncomeModal();
        }
    }

    async function deleteIncome(id) {
        if (!confirm('Are you sure you want to delete this income record?')) return;
        await fetch('{{ route("finance.incomes-destroy", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        loadIncomes();
    }

    loadIncomes();
</script>
@endpush