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
     * Get the full URL for the cover image
     */
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? url('public/books/' . $this->cover_image) : null;
    }

    /**
     * Get the full URL for the PDF
     */
    public function getBookPdfUrlAttribute()
    {
        return $this->book_pdf ? url('public/books/' . $this->book_pdf) : null;
    }
}
