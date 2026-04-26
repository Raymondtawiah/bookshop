<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CoachingMeetingLink;
use App\Mail\CoachingMeetingReminder;
use App\Mail\CoachingPaymentReceived;
use App\Models\CoachingBooking;
use App\Models\SiteSetting;
use App\Services\NotificationService;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CoachingController extends Controller
{
    protected $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    public function index()
    {
        return view('admin.coaching-booking');
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
            'package' => 'required|in:single,premium',
            'notes' => 'nullable|string',
        ]);

        // Prices in GHS (direct amounts)
        $packagePrice = $validated['package'] === 'premium' ? 249 : 129;

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

        // Use GHS for Paystack
        $paymentResult = $this->paystack->initializePayment(
            $booking->email,
            $packagePrice,
            $reference,
            'GHS',
            route('coaching.callback')
        );

        if ($paymentResult && isset($paymentResult['success']) && $paymentResult['success']) {
            return redirect($paymentResult['authorization_url']);
        }

        return redirect()->route('coaching.booking')->with('error', 'Failed to initialize payment. Please try again.');
    }

    public function callback(Request $request)
    {
        $reference = $request->reference;

        if (! $reference) {
            return redirect()->route('coaching.booking')->with('error', 'Payment reference not found.');
        }

        $booking = CoachingBooking::where('payment_reference', $reference)->first();

        if (! $booking) {
            return redirect()->route('coaching.booking')->with('error', 'Booking not found.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('home')->with('success', 'Your booking is confirmed!');
        }

        $verification = $this->paystack->verifyPayment($reference);

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
        $bookings = CoachingBooking::where('email', auth()->user()->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.my-bookings', compact('bookings'));
    }

    public function adminIndex()
    {
        $bookings = CoachingBooking::where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.coaching.index', compact('bookings'));
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

    public function sendReminder(CoachingBooking $booking)
    {
        if (! $booking->meeting_time) {
            return response()->json(['success' => false, 'message' => 'No meeting time set'], 400);
        }

        if (! $booking->meeting_link) {
            return response()->json(['success' => false, 'message' => 'No meeting link sent yet'], 400);
        }

        $minutesUntil = now()->diffInMinutes($booking->meeting_time, false);

        Mail::to($booking->email)->send(new CoachingMeetingReminder($booking, $minutesUntil));

        $booking->update(['reminder_sent_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Reminder sent successfully']);
    }

    public function toggleActive(Request $request)
    {
        $isActive = $request->boolean('is_active');

        SiteSetting::set('coaching_booking_active', $isActive ? 'true' : 'false');

        return back()->with('success', $isActive ? 'Booking page is now enabled.' : 'Booking page is now disabled.');
    }

    public function isActive()
    {
        return session('coaching_booking_active', true);
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
