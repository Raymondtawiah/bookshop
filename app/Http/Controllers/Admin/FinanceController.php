<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthorizesFinance;
use App\Models\CoachingBooking;
use App\Models\Expense;
use App\Models\FinanceRequest;
use App\Models\Income;
use App\Models\Order;
use App\Models\User;
use App\Models\WebinarRegistration;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    use AuthorizesFinance;

    public function __construct(private AttendanceService $attendanceService)
    {
    }

    public function dashboard(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $incomeTotal = Income::where('payment_status', 'paid')->sum('amount');
        $orderTotal = Order::where('payment_status', 'paid')->sum('total_amount');
        $webinarTotal = WebinarRegistration::where('payment_status', WebinarRegistration::STATUS_PAID)->sum('amount_paid');
        $coachingTotal = CoachingBooking::where('payment_status', 'paid')->sum('amount');
        $totalRevenue = $incomeTotal + $orderTotal + $webinarTotal + $coachingTotal;
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        $pendingPayments = Income::where('payment_status', 'pending')->sum('amount');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'total_revenue' => (float) $totalRevenue,
                    'total_expenses' => (float) $totalExpenses,
                    'net_profit' => (float) $netProfit,
                    'pending_payments' => (float) $pendingPayments,
                    'breakdown' => [
                        'income' => (float) $incomeTotal,
                        'orders' => (float) $orderTotal,
                        'webinars' => (float) $webinarTotal,
                        'coaching' => (float) $coachingTotal,
                    ],
                ],
            ]);
        }

        return view('finance.dashboard', compact('totalRevenue', 'totalExpenses', 'netProfit', 'pendingPayments', 'incomeTotal', 'orderTotal', 'webinarTotal', 'coachingTotal'));
    }

    public function incomeIndex(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $incomes = Income::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('customer_client_name', 'like', '%' . $request->search . '%')
                    ->orWhere('source', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('payment_status', $request->status);
            })
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('date', '<=', $request->end_date);
            })
            ->orderByDesc('date')
            ->paginate(20);

        return view('finance.income.index', compact('incomes'));
    }

    public function incomeStore(Request $request)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'customer_client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'payment_status' => 'required|in:paid,pending,failed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = $request->user()->id;

        Income::create($validated);

        return redirect()->route('finance.income')->with('success', 'Income record added successfully.');
    }

    public function incomeUpdate(Request $request, Income $income)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'customer_client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'payment_status' => 'required|in:paid,pending,failed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $income->update($validated);

        return redirect()->route('finance.income')->with('success', 'Income record updated successfully.');
    }

    public function incomeDestroy(Request $request, Income $income)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $income->delete();

        return redirect()->route('finance.income')->with('success', 'Income record deleted successfully.');
    }

    public function expensesIndex(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $query = Expense::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('expense_name', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('paid_to', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->filled('paid_to'), function ($query) use ($request) {
                $query->where('paid_to', 'like', '%' . $request->paid_to . '%');
            })
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('date', '<=', $request->end_date);
            });

        $filteredTotal = (clone $query)->sum('amount');

        $expenses = $query->orderByDesc('date')->paginate(20);

        $categories = ['Marketing', 'Development', 'Software', 'Team payments', 'Other'];

        $userRole = strtolower(trim($request->user()->role));
        $canEdit = $userRole === 'finance admin';

        return view('finance.expenses.index', compact('expenses', 'categories', 'filteredTotal', 'canEdit'));
    }

    public function expensesDownload(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $query = Expense::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('expense_name', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('paid_to', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->filled('paid_to'), function ($query) use ($request) {
                $query->where('paid_to', 'like', '%' . $request->paid_to . '%');
            })
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('date', '<=', $request->end_date);
            });

        $expenses = $query->orderByDesc('date')->get();
        $filteredTotal = $expenses->sum('amount');

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><title>Expenses Report</title>';
        $html .= '<style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #1f2937; }
            table { border-collapse: collapse; width: 100%; margin-top: 20px; }
            th, td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
            th { background-color: #f3f4f6; font-weight: bold; }
            .summary { margin-top: 20px; font-weight: bold; }
        </style></head><body>';
        $html .= '<h1>Expenses Report</h1>';
        $html .= '<p>Generated on: ' . now()->format('F d, Y H:i:s') . '</p>';

        if ($request->filled('search') || $request->filled('category') || $request->filled('paid_to') || $request->filled('start_date') || $request->filled('end_date')) {
            $html .= '<p><strong>Filters applied:</strong></p><ul>';
            if ($request->filled('search')) {
                $html .= '<li>Search: ' . htmlspecialchars($request->search) . '</li>';
            }
            if ($request->filled('category')) {
                $html .= '<li>Category: ' . htmlspecialchars($request->category) . '</li>';
            }
            if ($request->filled('paid_to')) {
                $html .= '<li>Paid To: ' . htmlspecialchars($request->paid_to) . '</li>';
            }
            if ($request->filled('start_date')) {
                $html .= '<li>Start Date: ' . htmlspecialchars($request->start_date) . '</li>';
            }
            if ($request->filled('end_date')) {
                $html .= '<li>End Date: ' . htmlspecialchars($request->end_date) . '</li>';
            }
            $html .= '</ul>';
        }

        $html .= '<table>';
        $html .= '<tr><th>Name</th><th>Paid To</th><th>Category</th><th>Amount</th><th>Date</th><th>Notes</th></tr>';
        foreach ($expenses as $expense) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($expense->expense_name) . '</td>';
            $html .= '<td>' . htmlspecialchars($expense->paid_to ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($expense->category) . '</td>';
            $html .= '<td>$' . number_format($expense->amount, 2) . '</td>';
            $html .= '<td>' . $expense->date->format('M d, Y') . '</td>';
            $html .= '<td>' . htmlspecialchars($expense->notes ?? '') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $html .= '<div class="summary">';
        $html .= '<p>Total Expenses: $' . number_format($filteredTotal, 2) . '</p>';
        $html .= '<p>Total Records: ' . $expenses->count() . '</p>';
        $html .= '</div>';

        $html .= '</body></html>';

        $filename = 'Expenses_Report_' . now()->format('Y-m-d') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-word',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function expensesStore(Request $request)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $validated = $request->validate([
            'expense_name' => 'required|string|max:255',
            'paid_to' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $validated['created_by'] = $request->user()->id;

        Expense::create($validated);

        return redirect()->route('finance.expenses')->with('success', 'Expense recorded successfully.');
    }

    public function expensesUpdate(Request $request, Expense $expense)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $validated = $request->validate([
            'expense_name' => 'required|string|max:255',
            'paid_to' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($validated);

        return redirect()->route('finance.expenses')->with('success', 'Expense updated successfully.');
    }

    public function expensesDestroy(Request $request, Expense $expense)
    {
        $this->authorizeFinance($request->user(), 'edit');

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('finance.expenses')->with('success', 'Expense deleted successfully.');
    }

    public function paymentsIndex(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $type = $request->query('type', 'all');

        $orders = Order::query()
            ->when($type === 'all' || $type === 'orders', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->get(['id', 'order_number', 'customer_name', 'total_amount', 'payment_status', 'created_at', 'currency'])
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'reference' => $order->order_number ?? '#' . $order->id,
                    'customer' => $order->customer_name ?? 'Guest',
                    'amount' => $order->total_amount,
                    'currency' => $order->currency ?? 'USD',
                    'status' => $order->payment_status,
                    'date' => $order->created_at,
                    'type' => 'Order',
                ];
            });

        $webinars = WebinarRegistration::query()
            ->when($type === 'all' || $type === 'webinars', function ($query) {
                $query->where('payment_status', WebinarRegistration::STATUS_PAID);
            })
            ->get(['id', 'amount_paid', 'payment_status', 'created_at'])
            ->map(function ($reg) {
                return [
                    'id' => $reg->id,
                    'reference' => 'WEB-' . $reg->id,
                    'customer' => $reg->user?->name ?? 'Guest',
                    'amount' => $reg->amount_paid,
                    'currency' => 'USD',
                    'status' => $reg->payment_status,
                    'date' => $reg->created_at,
                    'type' => 'Webinar',
                ];
            });

        $coaching = CoachingBooking::query()
            ->when($type === 'all' || $type === 'coaching', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->get(['id', 'amount', 'payment_status', 'created_at'])
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'reference' => 'COACH-' . $booking->id,
                    'customer' => $booking->user?->name ?? 'Guest',
                    'amount' => $booking->amount,
                    'currency' => 'USD',
                    'status' => $booking->payment_status,
                    'date' => $booking->created_at,
                    'type' => 'Coaching',
                ];
            });

        $payments = $orders->concat($webinars)->concat($coaching)
            ->sortByDesc('date')
            ->values();

        return view('finance.payments.index', compact('payments', 'type'));
    }

    public function reportsIndex(Request $request)
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

        $monthlyOrderTotal = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $monthlyWebinarTotal = WebinarRegistration::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('payment_status', WebinarRegistration::STATUS_PAID)
            ->sum('amount_paid');

        $monthlyCoachingTotal = CoachingBooking::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('payment_status', 'paid')
            ->sum('amount');

        $totalMonthlyRevenue = $monthlyIncome + $monthlyOrderTotal + $monthlyWebinarTotal + $monthlyCoachingTotal;

        $recentMonths = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $recentMonths[] = [
                'month' => $date->month,
                'year' => $date->year,
                'label' => $date->format('M Y'),
            ];
        }

        return view('finance.reports.index', compact('monthlyIncome', 'monthlyExpenses', 'profit', 'totalMonthlyRevenue', 'month', 'year', 'recentMonths'));
    }

    public function reportsDownload(Request $request)
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

        $monthName = \Carbon\Carbon::create(null, $month)->format('F');

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><title>Financial Report - ' . $monthName . ' ' . $year . '</title>';
        $html .= '<style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #1f2937; }
            table { border-collapse: collapse; width: 100%; margin-top: 20px; }
            th, td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
            th { background-color: #f3f4f6; font-weight: bold; }
            .summary { margin-top: 20px; }
            .label { font-weight: bold; }
        </style></head><body>';
        $html .= '<h1>Financial Report - ' . $monthName . ' ' . $year . '</h1>';
        $html .= '<p>Generated on: ' . now()->format('F d, Y H:i:s') . '</p>';

        $html .= '<div class="summary">';
        $html .= '<h2>Summary</h2>';
        $html .= '<table>';
        $html .= '<tr><td class="label">Monthly Income</td><td>$' . number_format($monthlyIncome, 2) . '</td></tr>';
        $html .= '<tr><td class="label">Monthly Expenses</td><td>$' . number_format($monthlyExpenses, 2) . '</td></tr>';
        $html .= '<tr><td class="label">Profit</td><td>$' . number_format($profit, 2) . '</td></tr>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '</body></html>';

        $filename = 'Financial_Report_' . $monthName . '_' . $year . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-word',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function settings(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $financeTeam = User::where('is_staff', true)
            ->whereIn('role', ['Finance Admin', 'Finance Member'])
            ->get();

        return view('finance.settings.index', compact('financeTeam'));
    }

    public function attendanceIndex(Request $request)
    {
        $user = $request->user();

        $attendances = $user->attendances()
            ->orderByDesc('attendance_date')
            ->paginate(20);

        $totalDays = $attendances->total();
        $presentDays = $user->attendances()->where('status', 'present')->count();
        $avgAttendance = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        $todayAttendance = $user->attendances()
            ->whereDate('attendance_date', now()->toDateString())
            ->first();

        return view('finance.attendance.index', compact('attendances', 'totalDays', 'presentDays', 'avgAttendance', 'todayAttendance'));
    }

    public function attendanceStore(Request $request)
    {
        $user = $request->user();

        $attendance = $this->attendanceService->markAttendance($user);

        return redirect()->route('finance.attendance')->with('success', 'Attendance submitted successfully. Awaiting admin approval.');
    }
}
