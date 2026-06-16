<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CoachingMeetingLink;
use App\Mail\CoachingMeetingReminder;
use App\Mail\CoachingPaymentPending;
use App\Mail\CoachingPaymentReceived;
use App\Models\CoachingBooking;
use App\Models\SiteSetting;
use App\Services\NotificationService;
use App\Services\StripeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CoachingController extends Controller
{
    protected $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function index()
    {
        return view('admin.coaching-booking');
    }

    public function bookingPage($plan)
    {
        // Validate the plan parameter
        if (! in_array($plan, ['team', 'single', 'premium'])) {
            return redirect()->route('coaching.booking')->with('error', 'Invalid plan selected.');
        }

        return view('admin.coaching-booking-form', compact('plan'));
    }

    public function getBookingStatus()
    {
        $isActive = SiteSetting::get('coaching_booking_active', 'true');

        return response()->json(['is_active' => $isActive === 'true']);
    }

    public function store(Request $request)
    {
        $isActive = SiteSetting::get('coaching_booking_active', 'true');
        if ($isActive !== 'true') {
            return redirect()->route('coaching.booking')->with('error', 'Booking is currently disabled.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'interview_type' => 'required|string|max:255',
            'interview_date' => 'required|date|after_or_equal:today',
            'interview_time' => 'required',
            'package' => 'required|in:team,single,premium',
            'notes' => 'nullable|string',
        ]);

        // Prices in USD
        $packagePrice = match ($validated['package']) {
            'team' => 49.99,
            'single' => 129.78,
            'premium' => 216.36,
            default => 129.78,
        };

        $reference = 'COACH-'.time().rand(1000, 9999);

        $booking = CoachingBooking::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'interview_type' => $validated['interview_type'],
            'interview_date' => $validated['interview_date'],
            'interview_time' => $validated['interview_time'],
            'package' => $validated['package'],
            'notes' => $validated['notes'] ?? null,
            'booking_token' => Str::random(32),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_reference' => $reference,
            'amount' => $packagePrice,
        ]);

        // Use Stripe for payment
        $paymentResult = $this->stripe->createCheckoutSession(
            $booking->email,
            $packagePrice,
            $reference,
            route('coaching.callback'),
            route('coaching.booking'),
            [],
            $validated['package'] === 'team' ? 'Team Coaching Plan' : ($validated['package'] === 'single' ? '1 Week Interview Intensive' : 'Full Coaching Program'),
            'Coaching session booking'
        );

        if ($paymentResult && isset($paymentResult['success']) && $paymentResult['success']) {
            return redirect($paymentResult['checkout_url']);
        }

        return redirect()->route('coaching.booking')->with('error', 'Failed to initialize payment. Please try again.');
    }

    public function callback(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (! $sessionId) {
            return redirect()->route('coaching.booking')->with('error', 'Payment session not found.');
        }

        $booking = CoachingBooking::where('payment_reference', $request->reference)->first();

        if (! $booking) {
            return redirect()->route('coaching.booking')->with('error', 'Booking not found.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('home')->with('success', 'Your booking is confirmed!');
        }

        $verification = $this->stripe->verifyPayment($sessionId);

        if ($verification && isset($verification['success']) && $verification['success']) {
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            Mail::to($booking->email)->send(new CoachingPaymentReceived($booking));

            // Send admin notification
            NotificationService::newCoachingBooking($booking);

            return redirect()->route('home')->with('success', 'Payment successful! Your coaching session is confirmed.');
        }

        $booking->update(['payment_status' => 'failed']);

        return redirect()->route('coaching.booking')->with('error', 'Payment verification failed. Please try again.');
    }

    public function myBookings()
    {
        // Check if coaching is enabled
        $isActive = SiteSetting::get('coaching_booking_active', 'true');
        if ($isActive !== 'true') {
            return redirect()->route('home')->with('error', 'Coaching booking is currently disabled.');
        }

        $bookings = CoachingBooking::where('email', auth()->user()->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.my-bookings', compact('bookings'));
    }

    public function adminIndex()
    {
        $query = CoachingBooking::query();

        // Search
        if (request()->has('search') && request()->input('search') !== '') {
            $search = request()->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by package
        if (request()->has('package') && request()->input('package') !== '') {
            $package = request()->input('package');
            if (in_array($package, ['team', 'single', 'premium'])) {
                $query->where('package', $package);
            }
        }

        // Filter by payment status
        if (request()->has('payment_status') && request()->input('payment_status') !== '') {
            $paymentStatus = request()->input('payment_status');
            if (in_array($paymentStatus, ['paid', 'pending', 'failed'])) {
                $query->where('payment_status', $paymentStatus);
            }
        }

        // Filter by status
        if (request()->has('status') && request()->input('status') !== '') {
            $status = request()->input('status');
            if (in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
                $query->where('status', $status);
            }
        }

        // Filter by date range
        if (request()->has('start_date') && ! empty(request()->input('start_date'))) {
            $query->whereDate('interview_date', '>=', request()->input('start_date'));
        }

        if (request()->has('end_date') && ! empty(request()->input('end_date'))) {
            $query->whereDate('interview_date', '<=', request()->input('end_date'));
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        // Statistics
        $totalBookings = CoachingBooking::count();
        $totalPaid = CoachingBooking::where('payment_status', 'paid')->count();
        $totalPending = CoachingBooking::where('payment_status', 'pending')->count();
        $totalCompleted = CoachingBooking::where('status', 'completed')->count();
        $totalRevenue = CoachingBooking::where('payment_status', 'paid')->sum('amount');

        return view('admin.coaching.index', compact(
            'bookings',
            'totalBookings',
            'totalPaid',
            'totalPending',
            'totalCompleted',
            'totalRevenue'
        ));
    }

    public function sendPaymentReminder(Request $request, CoachingBooking $booking)
    {
        // Validate that the booking is pending
        if ($booking->payment_status !== 'pending') {
            return back()->with('error', 'This booking is not pending payment.');
        }

        $validated = $request->validate([
            'deadline_datetime' => 'nullable|date',
        ]);

        // Generate a payment link (Stripe checkout session) for the booking
        $paymentResult = $this->stripe->createCheckoutSession(
            $booking->email,
            $booking->amount,
            $booking->payment_reference,
            route('coaching.callback'),
            route('coaching.booking'),
            [],
            $booking->package === 'team' ? 'Team Coaching Plan' : ($booking->package === 'single' ? '1 Week Interview Intensive' : 'Full Coaching Program'),
            'Coaching session booking'
        );

        if (! ($paymentResult && isset($paymentResult['success']) && $paymentResult['success'])) {
            return back()->with('error', 'Failed to generate payment link. Please try again.');
        }

        $paymentLink = $paymentResult['checkout_url'];

        // Get deadline datetime or use interview date
        $deadlineDatetime = $validated['deadline_datetime']
            ? Carbon::parse($validated['deadline_datetime'])
            : ($booking->interview_date ? Carbon::parse($booking->interview_date) : null);

        // Send the email
        Mail::to($booking->email)->send(new CoachingPaymentPending($booking, $paymentLink, $deadlineDatetime));

        return back()->with('success', 'Payment reminder sent successfully.');
    }

    public function adminShow(CoachingBooking $booking)
    {
        return view('admin.coaching.show', compact('booking'));
    }

    public function updateStatus(Request $request, CoachingBooking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy(CoachingBooking $booking)
    {
        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }

    public function sendMeetingLink(Request $request, CoachingBooking $booking)
    {
        $validated = $request->validate([
            'meeting_link' => 'required|url',
            'meeting_time' => 'required',
            'meeting_notes' => 'nullable|string',
        ]);

        // Save meeting details to the booking
        $booking->update([
            'meeting_link' => $validated['meeting_link'],
            'meeting_time' => $validated['meeting_time'],
            'meeting_notes' => $validated['meeting_notes'] ?? null,
            'status' => 'completed',
        ]);

        Mail::to($booking->email)->send(new CoachingMeetingLink($booking, $validated['meeting_link'], $validated['meeting_time'], $validated['meeting_notes'] ?? null));

        return back()->with('success', 'Meeting link sent to customer successfully.');
    }

    public function sendReminder(Request $request, CoachingBooking $booking)
    {
        $validated = $request->validate([
            'reminder_datetime' => 'nullable|date',
            'meeting_link' => 'nullable|url',
        ]);

        // Update meeting time if provided
        if ($validated['reminder_datetime']) {
            $booking->update(['meeting_time' => Carbon::parse($validated['reminder_datetime'])]);
        }

        // Update meeting link if provided
        if ($validated['meeting_link']) {
            $booking->update(['meeting_link' => $validated['meeting_link']]);
        }

        // Use provided datetime or fall back to meeting_time
        $reminderDatetime = $booking->fresh()->meeting_time;

        if (! $reminderDatetime) {
            return response()->json(['success' => false, 'message' => 'No meeting time set'], 400);
        }

        $meetingLink = $validated['meeting_link'] ?? $booking->fresh()->meeting_link;

        if (! $meetingLink) {
            return response()->json(['success' => false, 'message' => 'No meeting link provided'], 400);
        }

        $minutesUntil = now()->diffInMinutes($reminderDatetime, false);

        Mail::to($booking->email)->send(new CoachingMeetingReminder($booking, $minutesUntil, $reminderDatetime, $meetingLink));

        $booking->update(['reminder_sent_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Reminder sent successfully']);
    }

    public function getUpcomingMeetings()
    {
        $now = now();
        $upcoming = CoachingBooking::whereNotNull('meeting_time')
            ->where('meeting_time', '>', $now->copy()->subMinutes(30))
            ->where('meeting_time', '<=', $now->copy()->addMinutes(30))
            ->where('status', '!=', 'cancelled')
            ->orderBy('meeting_time')
            ->get();

        return response()->json([
            'upcoming' => $upcoming->map(fn ($booking) => [
                'id' => $booking->id,
                'name' => $booking->name,
                'email' => $booking->email,
                'meeting_time' => $booking->meeting_time,
                'meeting_link' => $booking->meeting_link,
                'minutes_until' => now()->diffInMinutes($booking->meeting_time),
            ]),
        ]);
    }
}
