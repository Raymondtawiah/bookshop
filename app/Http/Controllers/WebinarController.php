<?php

namespace App\Http\Controllers;

use App\Models\Webinar;
use Illuminate\Support\Facades\Auth;

class WebinarController extends Controller
{
    /**
     * List all active webinars.
     */
    public function index()
    {
        $webinars = Webinar::active()->upcoming()->paginate(12);

        return view('webinars.index', compact('webinars'));
    }

    /**
     * Show a specific webinar and handle access.
     */
    public function show(Webinar $webinar)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to access this webinar.');
        }

        // Admins should be redirected to admin view
        if ($user->is_admin) {
            return redirect()->route('admin.webinars.admin.show', $webinar);
        }

        $registration = $webinar->registrations()->where('user_id', $user->id)->first();

        return view('webinars.show', compact('webinar', 'registration'));
    }
}
