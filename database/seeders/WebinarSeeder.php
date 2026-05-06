<?php

namespace Database\Seeders;

use App\Models\WebinarSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing admin from AdminSeeder
        $admin = User::where('is_admin', true)->first();

        if (!$admin) {
            throw new \Exception('Admin user not found. Please run AdminSeeder first.');
        }

        // Create weekly webinar (every Friday 4-5 PM)
        $nextFriday = now()->copy();
        while ($nextFriday->dayOfWeek !== 5) { // 5 = Friday
            $nextFriday->addDay();
        }
        $nextFriday->setTime(16, 0, 0);

        WebinarSession::create([
            'title' => 'Weekly Visa Interview Webinar',
            'description' => 'Weekly Visa Interview Webinar. Join us every Friday, 4–5 PM. Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.',
            'webinar_link' => 'https://meet.google.com/fwk-hngm-jva',
            'scheduled_at' => $nextFriday,
            'duration_minutes' => 60,
            'price' => 30.00,
            'status' => 'active',
            'created_by' => $admin->id,
        ]);
    }
}
