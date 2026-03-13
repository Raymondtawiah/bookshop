<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'total_amount',
        'status',
        'personalized_pdf_path',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the order has a personalized PDF.
     */
    public function hasPersonalizedPdf(): bool
    {
        return !empty($this->personalized_pdf_path);
    }

    /**
     * Get the URL for downloading the personalized PDF.
     */
    public function getDownloadUrlAttribute(): ?string
    {
        if ($this->personalized_pdf_path) {
            return route('order.download', $this->id);
        }
        return null;
    }
}
