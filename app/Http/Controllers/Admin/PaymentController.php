<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthorizesFinance;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use AuthorizesFinance;

    public function index(Request $request)
    {
        $this->authorizeFinance($request->user(), 'view');

        return response()->json([
            'success' => true,
            'data' => Payment::orderByDesc('payment_date')->get(),
        ]);
    }

    public function show(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'view');

        return response()->json([
            'success' => true,
            'data' => Payment::findOrFail($id),
        ]);
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'customer' => 'sometimes|required|string|max:255',
            'payment_date' => 'sometimes|required|date',
            'amount' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|string|in:Paid,Pending',
            'payment_method' => 'sometimes|nullable|string|max:255',
            'reference' => 'sometimes|nullable|string|max:255',
        ]);

        $payment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully.',
            'data' => $payment,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeFinance($request->user(), 'edit');

        Payment::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.',
        ]);
    }
}
