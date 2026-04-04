<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'price',
        'category',
        'isbn',
        'pages',
        'published_year',
        'cover_image',
        'book_pdf',
        'is_free',
        'stock',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
        'stock' => 'integer',
    ];

    /**
     * Get the full URL for the cover image from storage
     */
    public function getCoverImageUrlAttribute()
    {
        if (! $this->cover_image) {
            return null;
        }

        // Check if it's already a full URL (external storage)
        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        // Use Laravel's Storage facade to generate URL with cache-busting
        $url = Storage::disk('public')->url('books/'.$this->cover_image);

        // Add timestamp to prevent browser caching
        return $url.'?v='.$this->updated_at->timestamp;
    }

    /**
     * Get the full URL for the PDF from storage
     */
    public function getBookPdfUrlAttribute()
    {
        if (! $this->book_pdf) {
            return null;
        }

        // Check if it's already a full URL (external storage)
        if (filter_var($this->book_pdf, FILTER_VALIDATE_URL)) {
            return $this->book_pdf;
        }

        // Use Laravel's Storage facade to generate URL with cache-busting
        $url = Storage::disk('public')->url('books/'.$this->book_pdf);

        // Add timestamp to prevent browser caching
        return $url.'?v='.$this->updated_at->timestamp;
    }
}
