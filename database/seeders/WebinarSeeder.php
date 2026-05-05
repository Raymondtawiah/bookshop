<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Webinar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get existing admin from AdminSeeder
            $admin = User::where('is_admin', true)->first();

            if (!$admin) {
                throw new \Exception('Admin user not found. Please run AdminSeeder first.');
            }

            // Create one webinar with Zoom link
            Webinar::create([
                'title' => 'Visa Interview Webinar',
                'description' => 'Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved. Weekly live sessions every Friday.',
                'webinar_link' => 'https://zoom.us/j/bookshop-' . Str::random(10),
                'scheduled_at' => now()->addDays(7),
                'duration_minutes' => 60,
                'status' => 'active',
                'created_by' => $admin->id,
            ]);
        });
    }
}
