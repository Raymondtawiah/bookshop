<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Update all webinar prices to 9.99
$updated = DB::table('webinar_sessions')->update(['price' => 9.99]);

echo "Updated {$updated} webinar(s) to price $9.99\n";

// Verify the update
$webinars = DB::table('webinar_sessions')->get(['id', 'title', 'price']);
foreach ($webinars as $webinar) {
    echo "Webinar ID {$webinar->id}: {$webinar->title} - $$webinar->price\n";
}
