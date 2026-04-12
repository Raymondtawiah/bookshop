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
     * Cover image URL (PUBLIC /books folder)
     */
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }

        return asset('books/' . $this->cover_image);
    }

    /**
     * PDF URL (storage/books folder)
     */
    public function getBookPdfUrlAttribute()
    {
        if (!$this->book_pdf) {
            return null;
        }

        return asset('books/' . $this->book_pdf);
    }
}