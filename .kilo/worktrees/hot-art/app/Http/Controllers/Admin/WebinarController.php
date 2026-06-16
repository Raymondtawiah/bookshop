<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WebinarNotificationMail;
use App\Mail\WebinarPaymentPending;
use App\Mail\WebinarPaymentSuccess;
use App\Mail\WebinarReminderMail;
use App\Models\NotificationRecipient;
use App\Models\SiteSetting;
use App\Models\WebinarNotification;
use App\Models\WebinarRegistration;
use App\Models\WebinarSession;
use App\Services\StripeService;
use App\Services\WebinarAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WebinarController extends Controller
{
    protected $stripe;

    protected WebinarAccessService $accessService;

    public function __construct(StripeService $stripe, WebinarAccessService $accessService)
    {
        $this->stripe = $stripe;
        $this->accessService = $accessService;
    }

    /**
     * List all webinars - Central Management Dashboard.
     */
    public function index(Request $request)
    {
        // Get all webinars for statistics only
        $webinars = WebinarSession::latest()->get();

        // Get filtered registrations - show ALL registrations regardless of payment status
        $registrationsQuery = WebinarRegistration::query()
            ->with(['webinar', 'user'])
            ->latest();

        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $registrationsQuery->where(function ($query) use ($searchTerm) {
                $query->where('full_name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('email', 'like', '%'.$searchTerm.'%')
                    ->orWhere('phone', 'like', '%'.$searchTerm.'%');
            });
        }

        // Apply payment status filter
        if ($request->has('payment_status') && $request->payment_status != '') {
            $registrationsQuery->where('payment_status', $request->payment_status);
        }

        // Apply attendance filter
        if ($request->has('attendance') && $request->attendance != '') {
            if ($request->attendance === 'attended') {
                $registrationsQuery->whereNotNull('joined_at');
            } elseif ($request->attendance === 'not_attended') {
                $registrationsQuery->whereNull('joined_at');
            }
        }

        // Apply date range filter
        if ($request->has('start_date') && $request->start_date != '') {
            $registrationsQuery->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $registrationsQuery->whereDate('created_at', '<=', $request->end_date);
        }

        $registrations = $registrationsQuery->get();

        // Calculate summary stats
        $totalWebinars = $webinars->count();
        $totalRegistrations = $registrations->count();
        $totalPaid = $registrations->where('payment_status', 'paid')->count();
        $totalPending = $registrations->where('payment_status', 'pending')->count();
        $totalRevenue = $registrations->where('payment_status', 'paid')->sum('amount_paid');
        $totalAttended = $registrations->whereNotNull('joined_at')->count();

        // Get registration form enabled setting
        $registrationFormEnabled = SiteSetting::get('webinar_registration_form_enabled', 'true') === 'true';

        return view('admin.webinars.index', compact(
            'webinars',
            'registrations',
            'totalWebinars',
            'totalRegistrations',
            'totalPaid',
            'totalPending',
            'totalRevenue',
            'totalAttended',
            'registrationFormEnabled'
        ));
    }

    /**
     * Show create webinar form.
     */
    public function create()
    {
        return view('admin.webinars.create');
    }

    /**
     * Show edit webinar form.
     */
    public function edit(WebinarSession $webinar)
    {
        return view('admin.webinars.edit', compact('webinar'));
    }

    /**
     * Store a new webinar.
     */
    public function store(Request $request)
    {
        $validated = $this->validateWebinar($request);
        $validated['created_by'] = auth()->id();

        WebinarSession::create($validated);

        return redirect()->route('admin.webinars.index')
            ->with('success', 'Webinar created successfully!');
    }

    /**
     * Update a webinar.
     */
    public function update(Request $request, WebinarSession $webinar)
    {
        $validated = $this->validateWebinar($request, false);

        $webinar->update($validated);

        return redirect()->route('admin.webinars.index')
            ->with('success', 'Webinar updated successfully!');
    }

    /**
     * Delete a webinar.
     */
    public function destroy(WebinarSession $webinar)
    {
        $webinar->delete();

        return redirect()->route('admin.webinars.index')
            ->with('success', 'Webinar deleted successfully!');
    }

    /**
     * Send payment reminder for pending webinar registration.
     */
    public function sendPaymentReminder(Request $request, WebinarSession $webinar, WebinarRegistration $registration)
    {
        // Validate that the registration belongs to the webinar
        if ($registration->webinar_id !== $webinar->id) {
            return back()->with('error', 'Registration does not belong to this webinar.');
        }

        // Validate that the registration is pending
        if ($registration->payment_status !== 'pending') {
            return back()->with('error', 'This registration is not pending payment.');
        }

        $validated = $request->validate([
            'reminder_date' => 'nullable|date',
        ]);

        // Generate a reference for the checkout session if it doesn't exist
        $reference = $registration->transaction_reference;
        if (! $reference) {
            $reference = 'WEB-'.$webinar->id.'-'.$registration->id.'-'.time();
        }

        // Generate a payment link (Stripe checkout session) for the registration
        $paymentResult = $this->stripe->createCheckoutSession(
            $registration->email,
            $registration->amount_paid,
            $reference,
            route('webinars.payment.callback'),
            route('webinars.index'),
            [],
            $registration->webinar->title,
            'Registration for webinar: '.$registration->webinar->title
        );

        if (! ($paymentResult && isset($paymentResult['success']) && $paymentResult['success'])) {
            return back()->with('error', 'Failed to generate payment link. Please try again.');
        }

        $paymentLink = $paymentResult['checkout_url'];

        // Send the email
        Mail::to($registration->email)->send(new WebinarPaymentPending($registration, $paymentLink, $validated['reminder_date'] ?? null));

        // Track reminder sent
        $registration->update([
            'last_reminder_sent' => now(),
            'reminder_count' => ($registration->reminder_count ?? 0) + 1,
        ]);

        return back()->with('success', 'Payment reminder sent successfully.');
    }

    /**
     * Send webinar reminder for paid webinar registration.
     */
    public function sendWebinarReminder(Request $request, WebinarSession $webinar, ?WebinarRegistration $registration = null)
    {
        $validated = $request->validate([
            'reminder_type' => 'nullable|in:24_hours,1_hour,15_minutes,post_webinar',
            'message' => 'nullable|string',
            'reminder_date' => 'nullable|date',
            'reminder_time' => 'nullable|string',
            'custom_message' => 'nullable|string',
        ]);

        // Use default reminder type if not provided (for dropdown button)
        $reminderType = $validated['reminder_type'] ?? '24_hours';
        $customMessage = $validated['custom_message'] ?? $validated['message'] ?? null;

        // Combine date and time for the reminder
        $reminderDateTime = null;
        if ($validated['reminder_date']) {
            $time = $validated['reminder_time'] ?? '09:00';
            $reminderDateTime = $validated['reminder_date'].' '.$time;
        }

        // Handle individual reminder
        if ($registration) {
            if ($registration->payment_status !== 'paid') {
                return back()->with('error', 'This registration is not paid.');
            }

            // Send direct webinar link (Zoom link) - no access restrictions
            $accessLink = $webinar->webinar_link;

            // Send a reminder email to the paid registrant
            Mail::to($registration->email)->send(new WebinarReminderMail($webinar, $registration, $reminderType, $accessLink, $customMessage, $reminderDateTime));

            // Track reminder sent
            $registration->update([
                'last_reminder_sent' => now(),
                'reminder_count' => ($registration->reminder_count ?? 0) + 1,
            ]);

            return back()->with('success', 'Webinar reminder sent successfully.');
        }

        // Handle bulk reminder to all paid registrations
        return $this->sendPaidReminder($request, $webinar);
    }

    /**
     * Send webinar reminder to all paid registrations for a webinar.
     */
    public function sendPaidReminder(Request $request, WebinarSession $webinar)
    {
        $validated = $request->validate([
            'reminder_type' => 'required|in:24_hours,1_hour,15_minutes,post_webinar',
            'message' => 'nullable|string',
            'reminder_date' => 'nullable|date',
            'reminder_time' => 'nullable|string',
        ]);

        $reminderType = $validated['reminder_type'];
        $customMessage = $validated['message'] ?? null;

        // Combine date and time for the reminder
        $reminderDateTime = null;
        if ($validated['reminder_date']) {
            $time = $validated['reminder_time'] ?? '09:00';
            $reminderDateTime = $validated['reminder_date'].' '.$time;
        }

        $paidRegistrations = $webinar->registrations()
            ->where('payment_status', 'paid')
            ->get();

        if ($paidRegistrations->isEmpty()) {
            return back()->with('error', 'There are no paid registrations to send reminders to.');
        }

        $sentCount = 0;
        $failedCount = 0;

        // Send direct webinar link (Zoom link) - no access restrictions
        $accessLink = $webinar->webinar_link;

        foreach ($paidRegistrations as $registration) {
            try {
                Mail::to($registration->email)->send(new WebinarReminderMail($webinar, $registration, $reminderType, $accessLink, $customMessage, $reminderDateTime));

                // Track reminder sent
                $registration->update([
                    'last_reminder_sent' => now(),
                    'reminder_count' => ($registration->reminder_count ?? 0) + 1,
                ]);

                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Failed to send webinar reminder to paid registration', [
                    'webinar_id' => $webinar->id,
                    'registration_id' => $registration->id,
                    'email' => $registration->email,
                    'reminder_type' => $reminderType,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $message = "Reminder sent to {$sentCount} paid attendee".($sentCount !== 1 ? 's' : '');
        if ($failedCount > 0) {
            $message .= ", {$failedCount} failed";

            return back()->with('warning', $message);
        }

        return back()->with('success', $message.'.');
    }

    /**
     * Toggle attended status for a webinar registration.
     */
    public function toggleAttended(Request $request, WebinarSession $webinar, WebinarRegistration $registration)
    {
        if ($registration->joined_at) {
            $registration->update(['joined_at' => null]);

            return back()->with('success', 'Attendance toggled to not attended.');
        } else {
            $registration->update(['joined_at' => now()]);

            return back()->with('success', 'Attendance toggled to attended.');
        }
    }

    /**
     * Validate webinar data.
     */
    protected function validateWebinar(Request $request, bool $isNew = true)
    {
        $rules = [
            'title' => $isNew ? 'required|string|max:255' : 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'webinar_link' => $isNew ? 'required|url' : 'sometimes|url',
            'is_registration_open' => 'sometimes|boolean',
            'price' => 'required|numeric|min:0',
            'scheduled_at' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive,scheduled,completed',
        ];

        $validator = Validator::make($request->all(), $rules);

        return $validator->validated();
    }

    /**
     * Show notification creation form.
     */
    public function createNotification(WebinarSession $webinar)
    {
        return view('admin.webinars.notifications', compact('webinar'));
    }

    /**
     * Resend webinar confirmation email to a registration.
     */
    public function resendEmail(WebinarSession $webinar, WebinarRegistration $registration)
    {
        // Validate that the registration belongs to the webinar
        if ($registration->webinar_id !== $webinar->id) {
            return back()->with('error', 'Registration does not belong to this webinar.');
        }

        // Validate that the registration is paid
        if ($registration->payment_status !== 'paid') {
            return back()->with('error', 'This registration is not paid.');
        }

        // Send direct webinar link (Zoom link) - no access restrictions
        $webinarLink = $webinar->webinar_link;

        // Send confirmation email
        try {
            \Mail::to($registration->email)->send(
                new WebinarPaymentSuccess($registration, $webinar, $webinarLink, $webinarLink)
            );

            // Mark email as sent successfully
            $registration->update([
                'email_sent_at' => now(),
                'email_attempts' => $registration->email_attempts + 1,
            ]);

            return back()->with('success', 'Webinar confirmation email resent successfully.');
        } catch (\Exception $e) {
            // Mark email as failed
            $registration->update([
                'email_failed_at' => now(),
                'email_error' => $e->getMessage(),
                'email_attempts' => $registration->email_attempts + 1,
            ]);

            return back()->with('error', 'Failed to resend email. Please try again.');
        }
    }

    /**
     * Delete a webinar registration (admin).
     */
    public function destroyRegistration(WebinarSession $webinar, WebinarRegistration $registration)
    {
        // Validate that the registration belongs to the webinar
        if ($registration->webinar_id !== $webinar->id) {
            return back()->with('error', 'Registration does not belong to this webinar.');
        }

        try {
            $registration->delete();

            return back()->with('success', 'Registration deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete webinar registration', [
                'registration_id' => $registration->id,
                'webinar_id' => $webinar->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete registration.');
        }
    }

    /**
     * Toggle completed status for a webinar registration.
     */
    public function toggleRegistrationFinished(Request $request, WebinarSession $webinar, WebinarRegistration $registration)
    {
        if ($registration->webinar_id !== $webinar->id) {
            return back()->with('error', 'Registration does not belong to this webinar.');
        }

        if ($registration->registration_status === 'completed') {
            $registration->update(['registration_status' => 'registered']);

            return back()->with('success', 'Registration status reset to registered.');
        }

        $registration->update(['registration_status' => 'completed']);

        return back()->with('success', 'Registration marked as finished.');
    }

    /**
     * Toggle webinar registration open/closed status (global or per-webinar).
     */
    public function toggleRegistration(Request $request, $identifier)
    {
        if ($identifier === 'global') {
            $current = SiteSetting::get('webinar_registration_open', 'true');
            $newValue = $current === 'true' ? 'false' : 'true';
            SiteSetting::set('webinar_registration_open', $newValue);

            $status = $newValue === 'true' ? 'open' : 'closed';

            return back()->with('success', "Webinar registration is now {$status} globally.");
        }

        $webinar = WebinarSession::findOrFail($identifier);
        $isOpen = $request->has('is_registration_open') && $request->is_registration_open == '1';
        $webinar->update([
            'is_registration_open' => $isOpen,
        ]);

        $status = $webinar->is_registration_open ? 'open' : 'closed';

        return back()->with('success', "Webinar registration is now {$status}.");
    }

    /**
     * Toggle global webinar registration visibility for customers.
     */
    public function toggleRegistrationVisibility(Request $request)
    {
        $current = SiteSetting::get('webinar_registration_visible', 'true');
        $newValue = $current === 'true' ? 'false' : 'true';
        SiteSetting::set('webinar_registration_visible', $newValue);

        $status = $newValue === 'true' ? 'visible' : 'hidden';

        return back()->with('success', "Webinar registration is now {$status} to customers.");
    }

    /**
     * Toggle webinar visibility on customer-facing page.
     */
    public function toggleVisibility(Request $request, WebinarSession $webinar)
    {
        $webinar->update([
            'is_visible' => ! $webinar->is_visible,
        ]);

        $status = $webinar->is_visible ? 'visible' : 'hidden';

        return back()->with('success', "Webinar is now {$status} on the customer page.");
    }

    /**
     * Toggle registration form visibility on customer webinar page.
     */
    public function toggleRegistrationForm(Request $request)
    {
        $isEnabled = $request->has('is_enabled') && $request->is_enabled == '1';
        $newValue = $isEnabled ? 'true' : 'false';
        SiteSetting::set('webinar_registration_form_enabled', $newValue);

        $status = $newValue === 'true' ? 'enabled' : 'disabled';

        return back()->with('success', "Registration form is now {$status} on the customer webinar page.");
    }

    /**
     * Store and send notification.
     */
    public function storeNotification(Request $request, WebinarSession $webinar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,urgent,schedule,zoom_update',
            'expires_at' => 'nullable|date|after:now',
        ], [
            'expires_at.after' => 'Expiration time must be in the future.',
        ]);

        $notification = WebinarNotification::create([
            'webinar_id' => $webinar->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'is_active' => true,
            'expires_at' => ($request->has('set_expiration') && $validated['expires_at']) ? $validated['expires_at'] : null,
            'created_by' => auth()->id(),
        ]);

        // Send email notifications to all registered attendees
        $this->sendEmailNotifications($webinar, $notification);

        return redirect()->route('admin.webinars.index', ['webinar_id' => $webinar->id])
            ->with('success', "Notification sent to {$webinar->total_paid_registrations} paid attendees via email and in-app!");
    }

    /**
     * Send email notifications to paid attendees only.
     */
    private function sendEmailNotifications(WebinarSession $webinar, WebinarNotification $notification)
    {
        // Get all paid attendees (including guest registrations)
        $attendees = $webinar->registrations()
            ->with('user')
            ->where('payment_status', 'paid');

        // Get paid registrations with users (for sending to user email)
        $registrationsWithUsers = (clone $attendees)->whereHas('user')->get();

        // Get paid guest registrations (no user account)
        $guestRegistrations = (clone $attendees)->whereNull('user_id')->get();

        // Send to registered users
        foreach ($registrationsWithUsers as $registration) {
            $user = $registration->user;

            // Skip if user doesn't have email
            if (! $user->email) {
                continue;
            }

            try {
                // Send the email
                \Mail::to($user->email)->send(new WebinarNotificationMail($webinar, $notification, $user));

                // Track successful delivery
                NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => $user->id,
                    'webinar_registration_id' => $registration->id,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

            } catch (\Exception $e) {
                // Track failed delivery
                NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => $user->id,
                    'webinar_registration_id' => $registration->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                // Log the error but continue with other emails
                \Log::error('Failed to send webinar notification email', [
                    'user_id' => $user->id,
                    'webinar_id' => $webinar->id,
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Send to guest registrations
        foreach ($guestRegistrations as $registration) {
            // Skip if no email
            if (! $registration->email) {
                continue;
            }

            try {
                // Send the email to guest
                \Mail::to($registration->email)->send(new WebinarNotificationMail($webinar, $notification, null));

                // Track successful delivery
                NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => null, // Guest registration
                    'webinar_registration_id' => $registration->id,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

            } catch (\Exception $e) {
                // Track failed delivery
                NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => null,
                    'webinar_registration_id' => $registration->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                \Log::error('Failed to send webinar notification email to guest', [
                    'registration_id' => $registration->id,
                    'webinar_id' => $webinar->id,
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Send notification to specific users who missed it.
     */
    public function sendNotificationToUsers(Request $request, WebinarSession $webinar, WebinarNotification $notification)
    {
        $request->validate([
            'user_ids' => 'required|array',
        ]);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($request->user_ids as $userId) {
            // Handle both registered users (user_id) and guest registrations (registration_id)
            $registration = $webinar->registrations()
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('id', $userId); // For guest registrations
                })
                ->where('payment_status', 'paid')
                ->with('user')
                ->first();

            if (! $registration) {
                $failedCount++;

                continue;
            }

            // Check if notification already sent to this user for this specific notification
            $existingRecipient = NotificationRecipient::where([
                'webinar_notification_id' => $notification->id,
                'user_id' => $registration->user_id ?? null,
                'webinar_registration_id' => $registration->id,
            ])->first();

            if ($existingRecipient) {
                // Skip if already sent
                continue;
            }

            try {
                // Send the email
                if ($registration->user) {
                    // Registered user
                    \Mail::to($registration->user->email)->send(new WebinarNotificationMail($webinar, $notification, $registration->user));

                    // Track successful delivery
                    NotificationRecipient::create([
                        'webinar_notification_id' => $notification->id,
                        'user_id' => $registration->user->id,
                        'webinar_registration_id' => $registration->id,
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                } else {
                    // Guest registration
                    \Mail::to($registration->email)->send(new WebinarNotificationMail($webinar, $notification, null));

                    // Track successful delivery
                    NotificationRecipient::create([
                        'webinar_notification_id' => $notification->id,
                        'user_id' => null, // Guest registration
                        'webinar_registration_id' => $registration->id,
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                }

                $sentCount++;

            } catch (\Exception $e) {
                // Track failed delivery
                NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => $registration->user_id ?? null,
                    'webinar_registration_id' => $registration->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                $failedCount++;
            }
        }

        return redirect()->route('admin.webinars.index', ['webinar_id' => $webinar->id])
            ->with('success', "Notification sent to {$sentCount} users".($failedCount > 0 ? " ({$failedCount} failed)" : ''));
    }
}
