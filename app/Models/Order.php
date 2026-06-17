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
        'email',
        'residence',
        'nationality',
        'contact',
        'payment_method',
        'payment_provider',
        'total_amount',
        'total_amount_usd',
        'currency',
        'exchange_rate',
        'status',
        'order_number',
        'payment_status',
        'paid_at',
        'pdf_sent',
        'pdf_sent_at',
        'book_offered',
        'book_offered_at',
        'offer_note',
        'order_items',
        'transaction_reference',
        'discount_code',
        'discount_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_amount_usd' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'paid_at' => 'datetime',
        'pdf_sent' => 'boolean',
        'pdf_sent_at' => 'datetime',
        'book_offered' => 'boolean',
        'book_offered_at' => 'datetime',
        'order_items' => 'array',
        'discount_amount' => 'decimal:2',
    ];

    public function getOrderItemsAttribute($value)
    {
        if (is_array($value) && ! empty($value) && ! is_string(reset($value))) {
            return collect($value);
        }

        if (is_string($value) && ! empty($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return collect($decoded);
            }
        }

        if (is_array($value) && count($value) === 1 && is_string(reset($value))) {
            $decoded = json_decode(reset($value), true);
            if (is_array($decoded)) {
                return collect($decoded);
            }
        }

        return collect([]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->attributes['total_amount'];
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return '$'.number_format($this->total_amount, 2);
    }

    public function getFormattedTotalAmountUsdAttribute(): string
    {
        return '$'.number_format($this->total_amount_usd ?? $this->total_amount, 2);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' && $this->paid_at !== null;
    }

    public function isBookOffered(): bool
    {
        return (bool) $this->book_offered;
    }
}
