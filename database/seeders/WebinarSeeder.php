<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WebinarSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebinarSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $admin = User::where('is_admin', true)->first();

            if (!$admin) {
                throw new \Exception('Admin user not found. Please run AdminSeeder first.');
            }

            $nextFriday = now();
            while ($nextFriday->dayOfWeek !== 5) {
                $nextFriday->addDay();
            }
            $nextFriday->setTime(16, 0);

            WebinarSession::create([
                'title' => 'Weekly Webinars',
                'description' => 'Weekly Visa Interview Webinar. Join every Friday 4–5 PM.',
                'webinar_link' => 'https://meet.google.com/fwk-hngm-jva',
                'scheduled_at' => $nextFriday,
                'duration_minutes' => 60,
                'status' => 'active',
                'created_by' => $admin->id,
            ]);
        });
    }
}