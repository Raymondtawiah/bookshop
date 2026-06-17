<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 9.99, 99.99),
            'category' => fake()->randomElement(['Visa Interview', 'Travel Guide', 'Study Abroad', 'Career Development']),
            'isbn' => fake()->isbn13(),
            'pages' => fake()->numberBetween(100, 500),
            'published_year' => fake()->numberBetween(2020, 2024),
            'cover_image' => null,
            'stock' => fake()->numberBetween(0, 100),
            'is_featured' => fake()->boolean(30), // 30% chance of being featured
        ];
    }
}
