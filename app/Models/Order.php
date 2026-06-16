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
    public function getOrderItemsAttribute($value)
    {
        // If already a proper array (from attribute casting)
        if (is_array($value) && !empty($value) && !is_string(reset($value))) {
            return collect($value);
        }
        
        // If it's a JSON string
        if (is_string($value) && !empty($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return collect($decoded);
            }
        }
        
        // If it's an array with a single JSON string element (double-encoded)
        if (is_array($value) && count($value) === 1 && is_string(reset($value))) {
            $decoded = json_decode(reset($value), true);
            if (is_array($decoded)) {
                return collect($decoded);
            }
        }
        
        return collect([]);
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
