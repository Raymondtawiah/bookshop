<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CoachingBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'interview_type' => 'required|string|in:visa_interview,job_interview,general_consultation',
            'interview_date' => 'required|date|after:today',
            'interview_time' => 'required|date_format:H:i',
            'package' => 'required|in:single,premium,team',
            'notes' => 'nullable|string|max:1000',
        ];

        // Add team booking validation rules
        if ($this->input('package') === 'team') {
            $rules['group_size'] = 'required|integer|min:1|max:5';
            $rules['booking_type'] = 'required|in:team';
        } else {
            $rules['group_size'] = 'nullable|integer|min:1|max:5';
            $rules['booking_type'] = 'nullable|in:individual';
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'group_size.required' => 'Group size is required for team bookings.',
            'group_size.max' => 'Team bookings cannot exceed 5 people.',
            'group_size.min' => 'Team bookings must have at least 1 person.',
            'booking_type.required' => 'Booking type is required for team bookings.',
            'booking_type.in' => 'Invalid booking type selected.',
            'package.in' => 'Invalid package selected.',
            'interview_date.after' => 'Interview date must be in the future.',
            'interview_time.date_format' => 'Interview time must be in HH:MM format.',
        ];
    }
}
