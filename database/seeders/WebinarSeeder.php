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
            // Use a fixed date for simplicity
            $webinarDate = now()->addDays(7)->setTime(16, 0, 0);
            
            echo "Creating webinar for: {$webinarDate->format('Y-m-d H:i:s')}\n";

            WebinarSession::create([
                'title' => 'Weekly Visa Interview Webinar',
                'description' => 'Weekly Visa Interview Webinar. Join us every Friday, 4–5 PM. Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.',
                'webinar_link' => 'https://meet.google.com/fwk-hngm-jva',
                'scheduled_at' => $webinarDate,
                'duration_minutes' => 60,
                'price' => 30.00,
                'status' => 'active',
            ]);

            echo "Webinar created successfully!\n";
            
        } catch (\Exception $e) {
            echo "Error in WebinarSeeder: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
