<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use Illuminate\Console\Command;

class CleanupOldNotifications extends Command
{
    protected $signature = 'notifications:cleanup';

    protected $description = 'Delete read notifications older than 30 days';

    public function handle(): int
    {
        $deleted = AdminNotification::where('is_read', true)
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        $this->info("Deleted {$deleted} old read notifications.");

        return Command::SUCCESS;
    }
}
