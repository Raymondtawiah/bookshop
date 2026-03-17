<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== SERVER DIAGNOSTICS ===<br><br>";

echo "PHP Version: " . phpversion() . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";

echo "<br>=== PATH CHECKS ===<br>";
echo "Current dir: " . __DIR__ . "<br>";

$storagePath = __DIR__ . '/storage/framework';
echo "Storage path exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "<br>";

$vendorPath = __DIR__ . '/vendor/autoload.php';
echo "Vendor exists: " . (file_exists($vendorPath) ? 'YES' : 'NO') . "<br>";

$bootstrapPath = __DIR__ . '/bootstrap/app.php';
echo "Bootstrap exists: " . (file_exists($bootstrapPath) ? 'YES' : 'NO') . "<br>";

$envPath = __DIR__ . '/.env';
echo ".env exists: " . (file_exists($envPath) ? 'YES' : 'NO') . "<br>";

// Load Laravel
if (file_exists($vendorPath)) {
    require $vendorPath;
    $app = require $bootstrapPath;
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "<br>=== LARAVEL LOADED ===<br>";
    echo "App Name: " . config('app.name') . "<br>";
    echo "App Env: " . config('app.env') . "<br>";
    echo "App Debug: " . (config('app.debug') ? 'true' : 'false') . "<br>";
    echo "App URL: " . config('app.url') . "<br>";
    
    echo "<br>=== SESSION CONFIG ===<br>";
    echo "Session Driver: " . config('session.driver') . "<br>";
    echo "Session Domain: " . config('session.domain') . "<br>";
    echo "Session Path: " . config('session.path') . "<br>";
    
    echo "<br>=== DATABASE CONFIG ===<br>";
    echo "DB Connection: " . config('database.default') . "<br>";
    
    echo "<br>=== ROUTES TEST ===<br>";
    try {
        $response = $app->handle(Illuminate\Http\Request::create('/login', 'GET'));
        echo "Login page status: " . $response->getStatusCode() . "<br>";
        if ($response->getStatusCode() == 200) {
            echo "Login page works!<br>";
        } else {
            echo "Login page returned: " . $response->getContent() . "<br>";
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "<br>";
        echo "Trace: " . $e->getTraceAsString() . "<br>";
    }
}
