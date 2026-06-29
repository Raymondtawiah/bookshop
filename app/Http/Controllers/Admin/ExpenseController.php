<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthorizesFinance;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    use AuthorizesFinance;

    public function index(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        return response()->json([
            'success' => true,
            'data' => Expense::orderByDesc('date')->get(),
        ]);
    }

    public function show(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'view');

        return response()->json([
            'success' => true,
            'data' => Expense::findOrFail($id),
        ]);
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'expense_name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|in:Marketing,Development,Software,Team payments,Other',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'notes' => 'sometimes|nullable|string',
            'receipt' => 'sometimes|nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

    public function destroy(Request $request, $id)
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
}
