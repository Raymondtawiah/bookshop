<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'user_name',
        'user_initials',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}
