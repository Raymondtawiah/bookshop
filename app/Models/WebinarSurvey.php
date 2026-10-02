<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebinarSurvey extends Model
{
    protected $fillable = [
        'webinar_id',
        'source',
        'additional_comments',
        'ip_address',
    ];

    protected $casts = [
        'webinar_id' => 'integer',
    ];

    public function webinar(): BelongsTo
    {
        return $this->belongsTo(WebinarSession::class, 'webinar_id');
    }
}
