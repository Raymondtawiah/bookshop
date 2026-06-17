<?php

namespace App\Console\Commands;

use App\Services\WebinarReminderService;
use Illuminate\Console\Command;

class SendWebinarReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webinar:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated webinar reminders to registrants';

    /**
     * Execute the console command.
     */
    public function handle(WebinarReminderService $reminderService): int
    {
        $this->info('Starting webinar reminder process...');

        try {
            // Send pre-webinar reminders
            $reminderService->sendAutomatedReminders();
            $this->info('✅ Pre-webinar reminders sent successfully');

            // Send post-webinar follow-ups
            $reminderService->sendPostWebinarFollowUp();
            $this->info('✅ Post-webinar follow-ups sent successfully');

            $this->info('🎉 Webinar reminder process completed successfully');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Failed to send webinar reminders: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
