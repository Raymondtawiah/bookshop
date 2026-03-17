<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Testing POST /login After Session Fix ===<br><br>";

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test POST /login
echo "Testing POST /login...<br>";
try {
    $request = Illuminate\Http\Request::create('/login', 'POST', [
        'email' => 'admin@example.com',
        'password' => 'password',
        '_token' => 'test-token-simulated',
    ]);
    $response = $app->handle($request);
    echo "Status: " . $response->getStatusCode() . "<br>";
    
    if ($response->isRedirect()) {
        echo "✅ Redirect: YES<br>";
        echo "Redirect URL: " . $response->headers->get('Location') . "<br>";
    } else {
        echo "Redirect: NO<br>";
        echo "Content: " . substr($response->getContent(), 0, 500) . "<br>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
}

echo "<br>=== Session Config Check ===<br>";
echo "Session Driver: " . config('session.driver') . "<br>";
echo "Session Domain: " . config('session.domain') . "<br>";
echo "Session SameSite: " . config('session.same_site') . "<br>";
