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
     * Get the external storage base URL from config
     */
    private function getExternalStorageBaseUrl()
    {
        // Configure this in your .env file: EXTERNAL_STORAGE_URL=https://srv2111-files.hstgr.io/...
        return config('filesystems.disks.external.url') ?? 
               'https://srv2111-files.hstgr.io/f5e93c1ee0caf648/files/public_html/storage/app/public/books';
    }

    /**
     * Get the full URL for the cover image from storage
     */
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) return null;
        
        // Check if it's already a full URL (external storage)
        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }
        
        // Check if file exists in local storage
        $localPath = 'books/' . $this->cover_image;
        if (Storage::disk('public')->exists($localPath)) {
            return Storage::disk('public')->url($localPath);
        }
        
        // File not found locally - use external CDN
        return $this->getExternalStorageBaseUrl() . '/' . $this->cover_image;
    }

    /**
     * Get the full URL for the PDF from storage
     */
    public function getBookPdfUrlAttribute()
    {
        if (!$this->book_pdf) return null;
        
        // Check if it's already a full URL (external storage)
        if (filter_var($this->book_pdf, FILTER_VALIDATE_URL)) {
            return $this->book_pdf;
        }
        
        // Check if file exists in local storage
        $localPath = 'books/' . $this->book_pdf;
        if (Storage::disk('public')->exists($localPath)) {
            return Storage::disk('public')->url($localPath);
        }
        
        // File not found locally - use external CDN
        return $this->getExternalStorageBaseUrl() . '/' . $this->book_pdf;
    }
}