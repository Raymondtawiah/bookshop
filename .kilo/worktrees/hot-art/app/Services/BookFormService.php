<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookFormService
{
    public function validateBookData(Request $request): array
    {
        $bookType = $request->input('book_type', 'cover');
        $isPdf = $bookType === 'pdf';

        Log::info('Book Creation Debug', [
            'book_type' => $bookType,
            'is_pdf' => $isPdf,
            'is_free value' => $request->input('is_free'),
        ]);

        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'cover_image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add price rules only for cover books
        if (! $isPdf) {
            $rules['price'] = 'required|numeric|min:0';
            $rules['isbn'] = 'nullable|string|max:20';
            $rules['pages'] = 'nullable|integer|min:1';
            $rules['published_year'] = 'nullable|integer|min:1000|max:2100';
            $rules['stock'] = 'nullable|integer|min:0';
            $rules['is_free'] = 'boolean';
        }

        // Add PDF rules for PDF books
        if ($isPdf) {
            $rules['book_pdfs'] = 'required|file|mimes:pdf|max:10240';
        }

        $validated = $request->validate($rules);

        return $this->processBookType($validated, $isPdf);
    }

    private function processBookType(array $data, bool $isPdf): array
    {
        if ($isPdf) {
            $data['is_free'] = true;
            $data['price'] = 0;
            // Remove fields not needed for PDF
            unset($data['stock'], $data['isbn'], $data['pages'], $data['published_year']);
        }

        $isFree = isset($data['is_free']) && $data['is_free'];
        $isFeatured = isset($data['is_featured']) && $data['is_featured'];

        if ($isFree && $isFeatured) {
            $data['is_featured'] = false;
        }

        return $data;
    }

    public function validateBookUpdate(Request $request): array
    {
        $bookType = $request->input('book_type', ($request->boolean('is_free') ? 'pdf' : 'cover'));
        $isPdf = $bookType === 'pdf';

        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'cover_image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if (! $isPdf) {
            $rules['price'] = 'required|numeric|min:0';
            $rules['isbn'] = 'nullable|string|max:20';
            $rules['pages'] = 'nullable|integer|min:1';
            $rules['published_year'] = 'nullable|integer|min:1000|max:2100';
            $rules['stock'] = 'nullable|integer|min:0';
        }

        if ($isPdf) {
            $rules['is_free'] = 'boolean';
            $rules['book_pdfs'] = 'nullable|file|mimes:pdf|max:10240';
        }

        $validated = $request->validate($rules);

        return $this->processBookType($validated, $isPdf);
    }
}
