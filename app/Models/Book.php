<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'price',
        'cover_image',
        'book_pdf',
        'is_free',
        'is_featured',
        'isbn',
        'pages',
        'published_year',
        'category',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
    ];

    public function getPriceAttribute(): float
    {
        return (float) $this->attributes['price'];
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₵'.number_format($this->price, 2);
    }

    /**
     * Check if book is available for free download
     */
    public function isFreePdf(): bool
    {
        return $this->is_free && $this->hasBookPdf();
    }

    /**
     * Check if book has a cover image
     */
    public function hasCoverImage(): bool
    {
        return ! empty($this->cover_image);
    }

    /**
     * Check if book has a PDF file
     */
    public function hasBookPdf(): bool
    {
        return ! empty($this->book_pdf);
    }

    /**
     * Get cover image URL
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->hasCoverImage()
            ? asset('public/books/'.$this->cover_image)
            : null;
    }

    /**
     * Get PDF URL
     */
    public function getBookPdfUrlAttribute(): ?string
    {
        return $this->hasBookPdf()
            ? route('product.download', $this->id)
            : null;
    }
}
