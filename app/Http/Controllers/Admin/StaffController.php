<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStaffRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = User::where('is_staff', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(StoreStaffRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'] ?? explode('@', $validated['email'])[0],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_staff' => true,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }
}
