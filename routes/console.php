<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule coaching reminder checks every 5 minutes
Schedule::command('coaching:send-reminders')->everyFiveMinutes();

// Schedule chat cleanup every hour
Schedule::command('chat:cleanup')->hourly();

// Schedule notification cleanup every hour (delete notifications older than 12 hours)
Schedule::command('notifications:cleanup')->hourly();
