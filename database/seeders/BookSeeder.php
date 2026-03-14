<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'description' => 'A classic American novel',
                'price' => 25.99,
                'is_featured' => true,
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'description' => 'A gripping tale of racial injustice',
                'price' => 29.99,
                'is_featured' => true,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'description' => 'A dystopian social science fiction',
                'price' => 24.99,
                'is_featured' => true,
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'description' => 'A romantic novel of manners',
                'price' => 22.99,
                'is_featured' => true,
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'description' => 'A story about teenage rebellion',
                'price' => 19.99,
                'is_featured' => false,
            ],
            [
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'description' => 'The first book in the Harry Potter series',
                'price' => 35.99,
                'is_featured' => true,
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'description' => 'An epic high fantasy novel',
                'price' => 45.99,
                'is_featured' => true,
            ],
            [
                'title' => 'Animal Farm',
                'author' => 'George Orwell',
                'description' => 'An allegorical novella',
                'price' => 18.99,
                'is_featured' => false,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
