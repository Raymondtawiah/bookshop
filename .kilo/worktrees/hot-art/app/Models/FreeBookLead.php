<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeBookLead extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'book_id',
        'book_title',
        'download_token',
        'downloaded_at',
        'notified_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
