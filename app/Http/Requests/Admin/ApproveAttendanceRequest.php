<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApproveAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->is_admin;
    }

    public function rules(): array
    {
        return [];
    }
}
