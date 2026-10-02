<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$announcements = App\Models\Announcement::all(['id', 'title', 'video_url']);

foreach ($announcements as $announcement) {
    echo $announcement->id . ' | ' . $announcement->title . ' | ' . $announcement->video_url . PHP_EOL;
}
