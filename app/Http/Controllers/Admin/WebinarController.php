<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Models\WebinarNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebinarController extends Controller
{
    /**
     * List all webinars.
     */
    public function index(Request $request)
    {
        $query = Webinar::query();

        if ($request->has('q')) {
            $query->where('title', 'like', '%'.$request->input('q').'%')
                ->orWhere('description', 'like', '%'.$request->input('q').'%');
        }

        $webinars = $query->latest()->paginate(20);

        return view('admin.webinars.index', compact('webinars'));
    }

    /**
     * Show create webinar form.
     */
    public function create()
    {
        return view('admin.webinars.create');
    }

    /**
     * Store a new webinar.
     */
    public function store(Request $request)
    {
        $validated = $this->validateWebinar($request);
        $validated['created_by'] = auth()->id();

        Webinar::create($validated);

        return redirect()->route('admin.webinars.index')
            ->with('success', 'Webinar created successfully!');
    }

    /**
     * Show a specific webinar with attendees.
     */
    public function show(Webinar $webinar)
    {
        $query = $webinar->registrations()->with('user');

        $paymentStatus = request()->get('payment_status');
        $registrationStatus = request()->get('registration_status');
        $joinedStatus = request()->get('joined');

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($registrationStatus) {
            $query->where('registration_status', $registrationStatus);
        }

        if ($joinedStatus === 'yes') {
            $query->whereNotNull('joined_at');
        } elseif ($joinedStatus === 'no') {
            $query->whereNull('joined_at');
        }

        $registrations = $query->latest()->paginate(50);

        return view('admin.webinars.show', compact('webinar', 'registrations'));
    }

    /**
     * Show edit webinar form.
     */
    public function edit(Webinar $webinar)
    {
        return view('admin.webinars.edit', compact('webinar'));
    }

    /**
     * Update a webinar.
     */
    public function update(Request $request, Webinar $webinar)
    {
        $validated = $this->validateWebinar($request, false);

        $webinar->update($validated);

        return redirect()->route('admin.webinars.index')
            ->with('success', 'Webinar updated successfully!');
    }

    /**
     * Delete a webinar.
     */
    public function destroy(Webinar $webinar)
    {
        $webinar->delete();

        return redirect()->route('admin.webinars.index')
            ->with('success', 'Webinar deleted successfully!');
    }

    /**
     * Show attendees for a webinar.
     */
    public function attendees(Webinar $webinar)
    {
        $registrations = $webinar->registrations()
            ->with('user')
            ->latest()
            ->paginate(50);

        return view('admin.webinars.attendees', compact('webinar', 'registrations'));
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
    public function createNotification(Webinar $webinar)
    {
        return view('admin.webinars.notifications', compact('webinar'));
    }

    /**
     * Store and send notification.
     */
    public function storeNotification(Request $request, Webinar $webinar)
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

        return redirect()->route('admin.webinars.admin.show', $webinar->id)
            ->with('success', "Notification sent to {$webinar->total_paid_registrations} paid attendees via email and in-app!");
    }

    /**
     * Send email notifications to paid attendees only.
     */
    private function sendEmailNotifications(Webinar $webinar, WebinarNotification $notification)
    {
        // Get only paid attendees with their user information
        $attendees = $webinar->registrations()
            ->with('user')
            ->where('payment_status', 'paid') // Only paid users
            ->whereHas('user') // Only get registrations with valid users
            ->get();

        foreach ($attendees as $registration) {
            $user = $registration->user;
            
            // Skip if user doesn't have email
            if (!$user->email) {
                continue;
            }

            try {
                // Send the email
                \Mail::to($user->email)->send(new \App\Mail\WebinarNotificationMail($webinar, $notification, $user));
                
                // Track successful delivery
                \App\Models\NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => $user->id,
                    'webinar_registration_id' => $registration->id,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                
            } catch (\Exception $e) {
                // Track failed delivery
                \App\Models\NotificationRecipient::create([
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Send notification to specific users who missed it.
     */
    public function sendNotificationToUsers(Request $request, Webinar $webinar, WebinarNotification $notification)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($request->user_ids as $userId) {
            $registration = $webinar->registrations()
                ->where('user_id', $userId)
                ->where('payment_status', 'paid')
                ->with('user')
                ->first();

            if (!$registration || !$registration->user) {
                $failedCount++;
                continue;
            }

            try {
                // Send the email
                \Mail::to($registration->user->email)->send(new \App\Mail\WebinarNotificationMail($webinar, $notification, $registration->user));
                
                // Track successful delivery
                \App\Models\NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => $registration->user->id,
                    'webinar_registration_id' => $registration->id,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                
                $sentCount++;
                
            } catch (\Exception $e) {
                // Track failed delivery
                \App\Models\NotificationRecipient::create([
                    'webinar_notification_id' => $notification->id,
                    'user_id' => $registration->user->id,
                    'webinar_registration_id' => $registration->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                
                $failedCount++;
            }
        }

        return redirect()->route('admin.webinars.admin.show', $webinar->id)
            ->with('success', "Notification sent to {$sentCount} users" . ($failedCount > 0 ? " ({$failedCount} failed)" : ''));
    }
}
