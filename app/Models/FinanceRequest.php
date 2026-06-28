<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'details',
        'status',
        'admin_response',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}