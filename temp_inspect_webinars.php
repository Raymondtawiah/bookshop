<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$webinars = \App\Models\WebinarSession::all(['id','title','price','payment_enabled','scheduled_at','is_visible','payment_provider']);

echo "WEBINARS\n";
foreach ($webinars as $w) {
    echo sprintf("%d | %s | price=%s | payment_enabled=%s | scheduled=%s | visible=%s | payment_provider=%s\n",
        $w->id,
        $w->title,
        $w->price,
        $w->payment_enabled ? 'true' : 'false',
        $w->scheduled_at,
        $w->is_visible ? 'true' : 'false',
        (string) ($w->payment_provider ?? 'null')
    );
}
