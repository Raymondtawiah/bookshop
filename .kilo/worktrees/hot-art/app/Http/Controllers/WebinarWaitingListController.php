<?php

namespace App\Http\Controllers;

use App\Models\WebinarSession;
use App\Models\WebinarWaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebinarWaitingListController extends Controller
{
    /**
     * Add user to webinar waiting list
     */
    public function join(Request $request, WebinarSession $webinar)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Please login to join waiting list'], 401);
        }

        // Check if webinar is full
        $totalSpots = 10; // Example: 10 spots per webinar
        $registeredCount = $webinar->registrations()->count();
        $waitingCount = WebinarWaitingList::where('webinar_session_id', $webinar->id)->count();

        if ($registeredCount + $waitingCount >= $totalSpots) {
            return response()->json(['error' => 'Webinar is full'], 400);
        }

        // Add to waiting list
        WebinarWaitingList::create([
            'user_id' => $user->id,
            'webinar_session_id' => $webinar->id,
            'email' => $user->email,
            'full_name' => $user->name,
            'is_guest' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'You have been added to the waiting list. You will be notified when spots become available.',
        ]);
    }

    /**
     * Get waiting list for a webinar
     */
    public function index(WebinarSession $webinar)
    {
        $waitingList = WebinarWaitingList::with('user')
            ->where('webinar_session_id', $webinar->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($waitingList);
    }

    /**
     * Remove user from waiting list
     */
    public function leave(Request $request, WebinarSession $webinar)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Please login to leave waiting list'], 401);
        }

        WebinarWaitingList::where('user_id', $user->id)
            ->where('webinar_session_id', $webinar->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'You have been removed from the waiting list.',
        ]);
    }
}
