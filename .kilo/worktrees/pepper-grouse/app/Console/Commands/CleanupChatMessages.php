<?php

namespace App\Console\Commands;

use App\Models\Chat;
use Illuminate\Console\Command;

class CleanupChatMessages extends Command
{
    protected $signature = 'chat:cleanup';
    protected $description = 'Delete chat messages older than 24 hours';

    public function handle()
    {
        $deleted = Chat::where('created_at', '<', now()->subHours(24))->delete();
        $this->info("Deleted {$deleted} chat messages older than 24 hours.");
        return 0;
    }
}