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

            // Simple date calculation for next Friday
            $scheduledAt = now()->addDays(5 - now()->dayOfWeek % 7)->setTime(16, 0, 0);
            
            echo "Creating webinar for: {$scheduledAt->format('Y-m-d H:i:s')}\n";

            // Check if webinar already exists
            $existingWebinar = WebinarSession::where('title', 'Weekly Visa Interview Webinar')
                ->where('scheduled_at', $scheduledAt)
                ->first();
                
            if ($existingWebinar) {
                echo "Webinar already exists for this week.\n";
                return;
            }

            WebinarSession::create([
                'title' => 'Weekly Visa Interview Webinar',
                'description' => 'Weekly Visa Interview Webinar. Join us every Friday, 4–5 PM. Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.',
                'webinar_link' => 'https://meet.google.com/fwk-hngm-jva',
                'scheduled_at' => $scheduledAt,
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
