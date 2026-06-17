<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class UpdateFeaturedBookSeeder extends Seeder
{
    public function run(): void
    {
        $book = Book::where('is_featured', true)->first();
        if ($book) {
            $book->title = 'Visa Interview Success Package';
            $book->author = 'Nathaniel Gyarteng';
            $book->price = 39.99;
            $book->description = '✔ Complete Visa Interview Preparation Book ✔ 30-Minute Mock Visa Interview ✔ Personalized Feedback and Coaching';
            $book->save();
            $this->command->info('Featured book updated successfully');
        } else {
            $this->command->warn('No featured book found');
        }
    }
}
