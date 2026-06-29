@extends('layouts.finance')

@section('title', 'Payments - Finance')

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
        <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
        <button onclick="openPaymentModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Record Payment
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full" id="payments-table">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="payments-tbody">
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<div id="payment-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4" id="payment-modal-title">Record Payment</h3>
        <form id="payment-form" onsubmit="savePayment(event)">
            <input type="hidden" id="payment-id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                <input type="text" id="payment-customer" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                <input type="date" id="payment-date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" id="payment-amount" required step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="payment-status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <input type="text" id="payment-method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reference</label>
                <input type="text" id="payment-reference" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Save</button>
                <button type="button" onclick="closePaymentModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadPayments() {
        const response = await fetch('{{ route("api.finance.payments") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        const tbody = document.getElementById('payments-tbody');
        if (data.success && data.data.length > 0) {
            tbody.innerHTML = data.data.map(payment => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">${payment.payment_date}</td>
                    <td class="px-6 py-4">${payment.customer}</td>
                    <td class="px-6 py-4">$${parseFloat(payment.amount).toFixed(2)}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full ${payment.status === 'Paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">
                            ${payment.status}
                        </span>
                    </td>
                    <td class="px-6 py-4">${payment.payment_method || '-'}</td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editPayment(${payment.id})" class="text-emerald-600 hover:text-emerald-800 text-sm mr-2">Edit</button>
                        <button onclick="deletePayment(${payment.id})" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No payment records found</td></tr>';
        }
    }

    function openPaymentModal() {
        document.getElementById('payment-modal').classList.remove('hidden');
        document.getElementById('payment-form').reset();
        document.getElementById('payment-id').value = '';
    }

    function closePaymentModal() {
        document.getElementById('payment-modal').classList.add('hidden');
    }

    async function savePayment(e) {
        e.preventDefault();
        const id = document.getElementById('payment-id').value;
        const data = {
            customer: document.getElementById('payment-customer').value,
            payment_date: document.getElementById('payment-date').value,
            amount: document.getElementById('payment-amount').value,
            status: document.getElementById('payment-status').value,
            payment_method: document.getElementById('payment-method').value,
            reference: document.getElementById('payment-reference').value,
        };

        const url = id ? `{{ route("api.finance.payments.update", ":id") }}`.replace(':id', id) : '{{ route("api.finance.payments.store") }}';
        const method = id ? 'PUT' : 'POST';

        await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });
        closePaymentModal();
        loadPayments();
    }

    async function editPayment(id) {
        const response = await fetch(`{{ route("api.finance.payments.show") }}/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            document.getElementById('payment-id').value = data.data.id;
            document.getElementById('payment-customer').value = data.data.customer;
            document.getElementById('payment-date').value = data.data.payment_date;
            document.getElementById('payment-amount').value = data.data.amount;
            document.getElementById('payment-status').value = data.data.status;
            document.getElementById('payment-method').value = data.data.payment_method || '';
            document.getElementById('payment-reference').value = data.data.reference || '';
            document.getElementById('payment-modal-title').textContent = 'Edit Payment';
            openPaymentModal();
        }
    }

    async function deletePayment(id) {
        if (!confirm('Are you sure you want to delete this payment record?')) return;
        await fetch(`{{ route("api.finance.payments.destroy") }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        });
        loadPayments();
    }

    loadPayments();
</script>
@endpush