<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachingBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'interview_type',
        'interview_date',
        'interview_time',
        'package',
        'notes',
        'booking_token',
        'status',
        'payment_status',
        'payment_reference',
        'amount',
    ];

    protected $casts = [
        'interview_date' => 'date',
        'amount' => 'float',
    ];
}
