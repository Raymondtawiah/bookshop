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
        'total_amount',
        'status',
        'order_number',
        'payment_status',
        'paid_at',
        'pdf_sent',
        'pdf_sent_at',
        'order_items',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'pdf_sent' => 'boolean',
        'pdf_sent_at' => 'datetime',
        'order_items' => 'array',
    ];

    /**
     * Get the order items as a collection
     */
    public function getOrderItemsAttribute()
    {
        return collect($this->order_items ?? []);
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
