<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class SampleBooksSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Visa Interview Guide',
                'author' => 'Bookshop',
                'description' => 'Your complete guide to visa interviews',
                'price_usd' => 15.00,
                'category' => 'Guide',
                'is_free' => false,
            ],
            [
                'title' => 'Coaching Manual',
                'author' => 'Bookshop',
                'description' => 'Professional coaching handbook',
                'price_usd' => 25.00,
                'category' => 'Manual',
                'is_free' => false,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
