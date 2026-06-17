<?php

namespace Database\Seeders;

use App\Models\WebinarSession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Set the webinar date to next Friday at 4:00 PM
            $webinarDate = $this->getNextFriday()->setTime(16, 0, 0);

            echo "Creating webinar for: {$webinarDate->format('Y-m-d H:i:s')}\n";

            WebinarSession::create([
                'title' => 'Weekly Visa Interview Webinar',
                'description' => 'Weekly Visa Interview Webinar. This webinar takes place every Friday at 4:00 PM. Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.',
                'webinar_link' => 'https://meet.google.com/fwk-hngm-jva',
                'scheduled_at' => $webinarDate,
                'duration_minutes' => 60,
                'price' => 9.99,
                'status' => 'active',
                'is_registration_open' => true,
                'is_visible' => true,
            ]);

            echo "Webinar created successfully!\n";

        } catch (\Exception $e) {
            echo 'Error in WebinarSeeder: '.$e->getMessage()."\n";
            throw $e;
        }
    }

    /**
     * Get the next Friday date.
     */
    private function getNextFriday()
    {
        $friday = now()->copy();

        // Carbon dayOfWeek: 0=Sunday, 5=Friday, 6=Saturday
        $daysUntilFriday = (5 - $friday->dayOfWeek + 7) % 7;

        // If today is Friday, get next Friday (7 days ahead)
        if ($daysUntilFriday === 0) {
            $daysUntilFriday = 7;
        }

        return $friday->addDays($daysUntilFriday);
    }
}
