<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebinarSession;
use App\Services\WebinarPayment\WebinarPaymentToggleServiceInterface;
use Illuminate\Http\Request;

class WebinarPaymentController extends Controller
{
    public function __construct(
        protected WebinarPaymentToggleServiceInterface $paymentToggleService
    ) {}

    public function toggle(Request $request, WebinarSession $webinar)
    {
        $isEnabled = $this->paymentToggleService->toggle($webinar);

        return back()->with('success', $isEnabled
            ? 'Payment has been enabled for this webinar.'
            : 'Payment has been disabled. Registration is now free.'
        );
    }

    public function bulkToggle(Request $request)
    {
        $request->validate([
            'webinar_ids' => 'required|array',
            'webinar_ids.*' => 'exists:webinar_sessions,id',
        ]);

        $enabled = $request->boolean('enabled', true);

        foreach ($request->webinar_ids as $webinarId) {
            $webinar = WebinarSession::findOrFail($webinarId);
            $enabled
                ? $this->paymentToggleService->enablePayment($webinar)
                : $this->paymentToggleService->disablePayment($webinar);
        }

        return back()->with('success', $enabled
            ? 'Payment has been enabled for selected webinars.'
            : 'Payment has been disabled for selected webinars.'
        );
    }
}
