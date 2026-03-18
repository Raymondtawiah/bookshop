<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed nationalities
        $this->call([
            NationalitySeeder::class,
            AdminSeeder::class
        ]);
    }
}
