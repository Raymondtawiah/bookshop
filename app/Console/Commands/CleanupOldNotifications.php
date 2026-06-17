<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use Illuminate\Console\Command;

class CleanupOldNotifications extends Command
{
    protected $signature = 'notifications:cleanup';

    protected $description = 'Delete notifications older than 12 hours';

    public function handle(): int
    {
        $deleted = AdminNotification::deleteOlderThan(12);

        $this->info("Deleted {$deleted} old notifications.");

        return Command::SUCCESS;
    }
}
