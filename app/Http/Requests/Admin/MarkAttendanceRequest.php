<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MarkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->is_staff;
    }

    public function rules(): array
    {
        return [];
    }
}
