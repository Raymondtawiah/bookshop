<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\WebinarSession;
use Illuminate\Support\Facades\Auth;

class WebinarController extends Controller
{
    /**
     * List all active webinars.
     */
    public function index()
    {
        $webinars = WebinarSession::active()->visible()->latest()->paginate(12);

        $registrationFormEnabled = SiteSetting::get('webinar_registration_form_enabled', 'true') === 'true';

        return view('webinars.index', compact('webinars', 'registrationFormEnabled'));
    }

    /**
     * Show a specific webinar and handle access.
     */
    public function show(WebinarSession $webinar)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to access this webinar.');
        }

        // Admins should be redirected to admin view
        if ($user->is_admin) {
            return redirect()->route('admin.webinars.index', ['webinar_id' => $webinar->id]);
        }

        $registration = $webinar->registrations()->where('user_id', $user->id)->first();

        return view('webinars.show', compact('webinar', 'registration'));
    }

    /**
     * Show webinar registration page.
     */
    public function registerPage(WebinarSession $webinar)
    {
        return view('webinars.register', compact('webinar'));
    }
}
