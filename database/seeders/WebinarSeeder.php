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
        try {
            // Get existing admin from AdminSeeder
            $admin = User::where('is_admin', true)->first();

            if (!$admin) {
                echo "Admin user not found. Please run AdminSeeder first.\n";
                return;
            }

            echo "Found admin user: {$admin->email}\n";

            // Calculate next Friday 4 PM
            $nextFriday = now()->copy();
            while ($nextFriday->dayOfWeek !== 5) { // 5 = Friday
                $nextFriday->addDay();
            }
            $nextFriday->setTime(16, 0, 0);
            
            echo "Creating webinar for: {$nextFriday->format('Y-m-d H:i:s')}\n";

            // Check if webinar already exists for this week
            $weekStart = $nextFriday->copy()->startOfWeek();
            $weekEnd = $nextFriday->copy()->endOfWeek();
            
            $existingWebinar = WebinarSession::where('title', 'Weekly Visa Interview Webinar')
                ->whereBetween('scheduled_at', [$weekStart, $weekEnd])
                ->first();
                
            if ($existingWebinar) {
                echo "Webinar already exists for this week (ID: {$existingWebinar->id}).\n";
                return;
            }

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

            echo "Webinar created successfully!\n";
            
        } catch (\Exception $e) {
            echo "Error in WebinarSeeder: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
