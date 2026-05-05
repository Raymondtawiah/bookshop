<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Webinar;
use App\Models\WebinarRegistration;
use App\Services\WebinarAccessService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebinarSeeder extends Seeder
{
    protected $accessService;

    public function __construct(WebinarAccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get or create admin user for webinars
            $admin = User::where('email', 'admin@bookshop.com')->first();
            if (!$admin) {
                $admin = User::create([
                    'name' => 'Admin User',
                    'email' => 'admin@bookshop.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);
            }

            // Create one webinar with Zoom link
            $webinar = Webinar::create([
                'title' => 'Business Growth Webinar',
                'description' => 'Join us for an exclusive webinar on business growth strategies.',
                'webinar_link' => 'https://zoom.us/j/bookshop-' . Str::random(10),
                'scheduled_at' => now()->addDays(7),
                'duration_minutes' => 60,
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            $createdWebinars = [$webinar];

            // Create sample paid registrations with access links
            $this->createSampleRegistrations($createdWebinars);

            // Create some demo users for testing
            $this->createDemoUsers();
        });
    }

    /**
     * Create sample registrations with access links
     */
    protected function createSampleRegistrations(array $webinars): void
    {
        $sampleUsers = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '+233241234567'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '+233242345678'],
            ['name' => 'Mike Johnson', 'email' => 'mike@example.com', 'phone' => '+233243456789'],
        ];

        foreach ($webinars as $webinar) {
            foreach ($sampleUsers as $userData) {
                // Create or find user
                $user = User::firstOrCreate([
                    'email' => $userData['email']
                ], [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);

                // Create registration
                $registration = WebinarRegistration::create([
                    'webinar_id' => $webinar->id,
                    'user_id' => $user->id,
                    'full_name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'registration_status' => 'registered',
                    'payment_status' => 'paid',
                    'amount_paid' => $webinar->current_price,
                    'paid_at' => now()->subHours(rand(1, 24)),
                    'joined_at' => now()->subMinutes(rand(5, 60)),
                ]);

                // Generate encrypted access link
                $this->accessService->generateAccessLink($registration);
            }
        }
    }

    /**
     * Create demo users for testing
     */
    protected function createDemoUsers(): void
    {
        $demoUsers = [
            [
                'name' => 'Test User 1',
                'email' => 'test1@bookshop.com',
                'password' => 'password123',
            ],
            [
                'name' => 'Test User 2',
                'email' => 'test2@bookshop.com',
                'password' => 'password123',
            ],
            [
                'name' => 'Test User 3',
                'email' => 'test3@bookshop.com',
                'password' => 'password123',
            ],
        ];

        foreach ($demoUsers as $userData) {
            User::firstOrCreate([
                'email' => $userData['email']
            ], [
                'name' => $userData['name'],
                'password' => bcrypt($userData['password']),
                'email_verified_at' => now(),
            ]);
        }
    }
}
