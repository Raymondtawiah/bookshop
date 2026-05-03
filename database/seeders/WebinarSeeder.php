<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Webinar;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists for webinar creation
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@nathanielgyarteng.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]
        );

        Webinar::create([
            'title' => 'Introduction to Data Science',
            'description' => 'Learn the fundamentals of data science, including data analysis, visualization, and machine learning basics. Perfect for beginners looking to start their data science journey.',
            'webinar_link' => 'https://zoom.us/j/1234567890?pwd=sample123',
            'price' => 49.99,
            'scheduled_at' => Carbon::now()->addDays(7),
            'duration_minutes' => 120,
            'status' => 'active',
            'created_by' => $adminUser->id,
        ]);

        Webinar::create([
            'title' => 'Advanced Python Programming',
            'description' => 'Take your Python skills to the next level with advanced topics including decorators, context managers, async programming, and performance optimization.',
            'webinar_link' => 'https://zoom.us/j/0987654321?pwd=sample456',
            'price' => 0,
            'scheduled_at' => Carbon::now()->addDays(14),
            'duration_minutes' => 90,
            'status' => 'active',
            'created_by' => $adminUser->id,
        ]);

        Webinar::create([
            'title' => 'Web Development with Laravel',
            'description' => 'Build robust web applications using Laravel framework. Covers routing, controllers, Eloquent ORM, authentication, and deployment strategies.',
            'webinar_link' => 'https://zoom.us/j/1122334455?pwd=sample789',
            'price' => 79.99,
            'scheduled_at' => Carbon::now()->addDays(21),
            'duration_minutes' => 180,
            'status' => 'scheduled',
            'created_by' => $adminUser->id,
        ]);
    }
}
