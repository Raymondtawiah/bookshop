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
        $webinars = WebinarSession::visible()->latest()->paginate(12);

        $registrationFormEnabled = SiteSetting::get('webinar_registration_form_enabled', 'true') === 'true';
        $curriculum = SiteSetting::get('webinar_curriculum', [
            'heading' => 'What you\'ll learn',
            'subheading' => 'Webinar curriculum',
            'cards' => [
                ['title' => 'Common interview questions', 'message' => 'Learn the questions officers ask most often and how to answer them clearly and confidently.'],
                ['title' => 'Document preparation', 'message' => 'Know exactly which documents you need and how to organize them so nothing holds you back.'],
                ['title' => 'Body language & confidence', 'message' => 'Master the posture, tone, and eye contact that project confidence in under a minute.'],
                ['title' => 'Red flags to avoid', 'message' => 'Learn the common mistakes that lead to denials — and how to steer clear of them entirely.'],
                ['title' => 'Mock interview practice', 'message' => 'Join live mock interviews and get real-time feedback from experts on your performance.'],
                ['title' => 'Success stories', 'message' => 'Hear real approvals from past attendees and the exact strategies that worked for them.'],
            ],
        ]);

        if (is_string($curriculum)) {
            $curriculum = json_decode($curriculum, true) ?: $curriculum;
        }

        return view('webinars.index', compact('webinars', 'registrationFormEnabled', 'curriculum'));
    }

    /**
     * Show a specific webinar and handle access.
     */
    public function show(WebinarSession $webinar)
    {
        return redirect()->route('webinars.register.page', $webinar);
    }

    /**
     * Show webinar registration page.
     */
    public function registerPage(WebinarSession $webinar)
    {
        return view('webinars.register', compact('webinar'));
    }
}
