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
        if (!$this->cover_image) return null;
        return Storage::disk('public')->url('books/' . $this->cover_image);
    }

    /**
     * Get the full URL for the PDF from storage
     */
    public function getBookPdfUrlAttribute()
    {
        if (!$this->book_pdf) return null;
        return Storage::disk('public')->url('books/' . $this->book_pdf);
    }
}