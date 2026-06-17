<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of all customers.
     */
    public function index(): View
    {
        // Fetch all users that are not admins (customers)
        $customers = User::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }
}
