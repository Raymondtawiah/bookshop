<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebinarSession;
use App\Models\WebinarSurvey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WebinarSurveyController extends Controller
{
    public function store(Request $request, $webinarId): JsonResponse
    {
        $webinar = WebinarSession::findOrFail($webinarId);

        $validated = $request->validate([
            'source' => ['required', 'string', 'max:255', 'in:tiktok,facebook,instagram,other'],
            'additional_comments' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['source'] === 'other' && empty($validated['additional_comments'])) {
            throw ValidationException::withMessages([
                'additional_comments' => 'Please explain how you heard about this webinar.',
            ]);
        }

        $survey = WebinarSurvey::create([
            'webinar_id' => $webinar->id,
            'source' => $validated['source'],
            'additional_comments' => $validated['additional_comments'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $survey,
        ], 201);
    }
}
