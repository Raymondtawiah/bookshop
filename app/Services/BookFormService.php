<?php

namespace App\Services;

use Illuminate\Http\Request;

class BookFormService
{
    public function validateBookData(Request $request): array
    {
        $isFree = $request->boolean('is_free');

        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:2100',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'book_pdfs' => $isFree ? 'required|array|min:1' : 'nullable|array',
            'book_pdfs.*' => 'mimes:pdf|max:10240',
        ];

        $validated = $request->validate($rules);

        return $this->processBookType($validated);
    }

    private function processBookType(array $data): array
    {
        $isFree = isset($data['is_free']) && $data['is_free'];
        $isFeatured = isset($data['is_featured']) && $data['is_featured'];

        if ($isFree && $isFeatured) {
            $data['is_free'] = true;
            $data['is_featured'] = false;
            $data['price'] = 0;
        } elseif ($isFree) {
            $data['price'] = 0;
        } elseif ($isFeatured) {
            $data['is_free'] = false;
        }

        return $data;
    }

    public function validateBookUpdate(Request $request): array
    {
        $isFree = $request->boolean('is_free');

        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:2100',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'book_pdf' => 'nullable|mimes:pdf|max:10240',
        ];

        $bookPdfFile = $request->file('book_pdf');
        if ($isFree && empty($bookPdfFile)) {
            $request->validate([
                'book_pdf' => 'required'
            ], [
                'book_pdf.required' => 'Please upload a PDF file for free books.'
            ]);
        }

        $validated = $request->validate($rules);

        return $this->processBookType($validated);
    }
}