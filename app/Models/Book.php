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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
    ];

    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }

        return asset('public/books/' . $this->cover_image);
    }

    public function getBookPdfUrlAttribute()
    {
        if (!$this->book_pdf) {
            return null;
        }

        return asset('public/books/' . $this->book_pdf);
    }

    public function hasCoverImage(): bool
    {
        return !empty($this->cover_image);
    }
}