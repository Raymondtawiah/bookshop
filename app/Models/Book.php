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

        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        return asset('storage/books/'.$this->cover_image);
    }

    /**
     * Get the full URL for the PDF from storage
     */
    public function getBookPdfUrlAttribute()
    {
        if (! $this->book_pdf) {
            return null;
        }

        if (filter_var($this->book_pdf, FILTER_VALIDATE_URL)) {
            return $this->book_pdf;
        }

        return '/storage/books/'.$this->book_pdf;
    }
}
