<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing Laravel...<br>";

require __DIR__.'/vendor/autoload.php';

echo "Vendor loaded<br>";

$app = require __DIR__.'/bootstrap/app.php';

echo "Bootstrap loaded<br>";

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Kernel bootstrapped<br>";
echo "App env: " . config('app.env') . "<br>";
echo "App debug: " . (config('app.debug') ? 'true' : 'false') . "<br>";
echo "Success!";
