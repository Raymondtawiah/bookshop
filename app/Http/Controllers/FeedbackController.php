<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class FeedbackController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'email' => 'nullable|email|max:255',
        ]);

        if (Session::has('feedback_submitted')) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted feedback.',
            ], 429);
        }

        $rating = (int) $validated['rating'];
        $comment = $validated['comment'] ?? null;
        $email = $validated['email'] ?? null;

        try {
            Mail::to('raymondtawiah23@gmail.com')->send(
                new FeedbackSubmitted($rating, $comment, $email)
            );
        } catch (\Throwable $e) {
            Log::error('Feedback email failed: '.$e->getMessage(), [
                'rating' => $rating,
                'email' => $email,
            ]);
        }

        Session::put('feedback_submitted', true);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!',
        ]);
    }
}
