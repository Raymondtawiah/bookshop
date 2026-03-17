<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        ]);

        // Create Admin User (hardcoded - not registrable)
        User::firstOrCreate(
            ['email' => 'raymondtawiah23@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
    }
}
