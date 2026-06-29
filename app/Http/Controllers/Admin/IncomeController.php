<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthorizesFinance;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    use AuthorizesFinance;

    public function index(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        return response()->json([
            'success' => true,
            'data' => Income::orderByDesc('date')->get(),
        ]);
    }

    public function show(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'view');

        return response()->json([
            'success' => true,
            'data' => Income::findOrFail($id),
        ]);
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $income = Income::findOrFail($id);

        $validated = $request->validate([
            'source' => 'sometimes|required|string|in:Consulting,Course,Sponsorship,Other',
            'customer_client_name' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'payment_status' => 'sometimes|required|string|in:paid,pending',
            'notes' => 'sometimes|nullable|string',
        ]);

        $income->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Income updated successfully.',
            'data' => $income,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        Income::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Income deleted successfully.',
        ]);
    }
}
