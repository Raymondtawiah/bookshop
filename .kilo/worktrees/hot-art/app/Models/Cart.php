<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'product_name',
        'unit_price',
        'quantity',
    ];

    /**
     * Get the user that owns the cart item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book associated with this cart item
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the cart item has a PDF template
     */
    public function hasPdfTemplate(): bool
    {
        return $this->book && $this->book->pdf_file;
    }

    public function getUnitPriceAttribute(): float
    {
        return (float) $this->attributes['unit_price'];
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return '$'.number_format($this->unit_price, 2);
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return '$'.number_format($this->total_price, 2);
    }
}
