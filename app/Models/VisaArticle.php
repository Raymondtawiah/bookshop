<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaArticle extends Model
{
    protected $fillable = [
        'country',
        'visa_type',
        'title',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByVisaType($query, $visaType)
    {
        if ($visaType) {
            return $query->where('visa_type', $visaType);
        }

        return $query;
    }
}
