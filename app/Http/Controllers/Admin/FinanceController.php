<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthorizesFinance;
use App\Models\CoachingBooking;
use App\Models\Expense;
use App\Models\FinanceRequest;
use App\Models\Income;
use App\Models\Order;
use App\Models\WebinarRegistration;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    use AuthorizesFinance;

    public function __construct(private AttendanceService $attendanceService) {}

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

    public function attendanceData(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        $attendances = $this->attendanceService->getUserHistory($request->user());

        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
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
}
