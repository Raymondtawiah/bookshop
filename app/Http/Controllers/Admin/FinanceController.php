<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\FinanceRequest;
use App\Models\Income;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    public function dashboard(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $totalRevenue = Income::where('payment_status', 'paid')->sum('amount');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        $pendingPayments = Income::where('payment_status', 'pending')->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => (float) $totalRevenue,
                'total_expenses' => (float) $totalExpenses,
                'net_profit' => (float) $netProfit,
                'pending_payments' => (float) $pendingPayments,
            ],
        ]);
    }

    public function dashboardWeb(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');
        return view('finance.dashboard');
    }

    public function attendanceWeb(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');
        return view('finance.attendance');
    }

    public function attendanceData(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $attendances = $request->user()->attendances()
            ->orderByDesc('date')
            ->limit(30)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }

    public function incomesWeb(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');
        return view('finance.incomes');
    }

    public function expensesWeb(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');
        return view('finance.expenses');
    }

    public function paymentsWeb(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');
        return view('finance.payments');
    }

    public function reportsWeb(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');
        return view('finance.reports');
    }

    public function settingsWeb(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');
        return view('finance.settings');
    }

    public function requestStore(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        $financeRequest = FinanceRequest::create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'details' => $validated['details'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request submitted successfully.',
            'data' => $financeRequest,
        ], 201);
    }

    public function myRequests(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $requests = FinanceRequest::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }

    public function incomeIndex(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $incomes = Income::orderByDesc('date')->get();

        return response()->json([
            'success' => true,
            'data' => $incomes,
        ]);
    }

    public function incomeShow(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'view');

        $income = Income::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $income,
        ]);
    }

    public function incomeStore(Request $request)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $validated = $request->validate([
            'source' => 'required|string|in:Consulting,Course,Sponsorship,Other',
            'customer_client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'payment_status' => 'required|string|in:paid,pending',
            'notes' => 'nullable|string',
        ]);

        $income = Income::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Income added successfully.',
            'data' => $income,
        ], 201);
    }

    public function incomeUpdate(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $income = Income::findOrFail($id);

        $validated = $request->validate([
            'source' => 'sometimes|required|string|in:Consulting,Course,Sponsorship,Other',
            'customer_client_name' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'payment_status' => 'sometimes|required|string|in:paid,pending',
            'notes' => 'nullable|string',
        ]);

        $income->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Income updated successfully.',
            'data' => $income,
        ]);
    }

    public function incomeDestroy(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $income = Income::findOrFail($id);
        $income->delete();

        return response()->json([
            'success' => true,
            'message' => 'Income deleted successfully.',
        ]);
    }

    public function expenseIndex(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $expenses = Expense::orderByDesc('date')->get();

        return response()->json([
            'success' => true,
            'data' => $expenses,
        ]);
    }

    public function expenseShow(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'view');

        $expense = Expense::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $expense,
        ]);
    }

    public function expenseStore(Request $request)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $validated = $request->validate([
            'expense_name' => 'required|string|max:255',
            'category' => 'required|string|in:Marketing,Development,Software,Team payments,Other',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = Expense::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Expense added successfully.',
            'data' => $expense,
        ], 201);
    }

    public function expenseUpdate(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'expense_name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|in:Marketing,Development,Software,Team payments,Other',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Expense updated successfully.',
            'data' => $expense,
        ]);
    }

    public function expenseDestroy(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $expense = Expense::findOrFail($id);
        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully.',
        ]);
    }

    public function paymentIndex(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $payments = Payment::orderByDesc('payment_date')->get();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    public function paymentStore(Request $request)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $validated = $request->validate([
            'customer' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:Paid,Pending',
            'payment_method' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        $payment = Payment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully.',
            'data' => $payment,
        ], 201);
    }

    public function paymentShow(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'view');

        $payment = Payment::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }

    public function paymentUpdate(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'customer' => 'sometimes|required|string|max:255',
            'payment_date' => 'sometimes|required|date',
            'amount' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|string|in:Paid,Pending',
            'payment_method' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        $payment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully.',
            'data' => $payment,
        ]);
    }

    public function paymentDestroy(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.',
        ]);
    }

    public function reportIndex(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        $monthlyIncome = Income::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('payment_status', 'paid')
            ->sum('amount');

        $monthlyExpenses = Expense::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $profit = $monthlyIncome - $monthlyExpenses;

        return response()->json([
            'success' => true,
            'data' => [
                'month' => (int) $month,
                'year' => (int) $year,
                'monthly_income' => (float) $monthlyIncome,
                'monthly_expenses' => (float) $monthlyExpenses,
                'profit' => (float) $profit,
            ],
        ]);
    }

    protected function authorizeFinance($user, string $permission = 'view')
    {
        if (! $user) {
            abort(401, 'Unauthorized.');
        }

        // Allow admins full access
        if ($user->is_admin) {
            return true;
        }

        $role = $user->role;

        // Finance Admin has full access
        if ($role === 'Finance Admin') {
            return true;
        }

        // Finance Member can only view
        if ($role === 'Finance Member' && $permission === 'view') {
            return true;
        }

        abort(403, 'Unauthorized.');
    }
}