<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Testing Routes Directly ===<br><br>";

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test GET /login
echo "Testing GET /login...<br>";
try {
    $request = Illuminate\Http\Request::create('/login', 'GET');
    $response = $app->handle($request);
    echo "Status: " . $response->getStatusCode() . "<br>";
    if ($response->getStatusCode() == 200) {
        echo "Content length: " . strlen($response->getContent()) . " chars<br>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
}

echo "<br>Testing POST /login (simulated)...<br>";
try {
    $request = Illuminate\Http\Request::create('/login', 'POST', [
        'email' => 'test@example.com',
        'password' => 'password',
        '_token' => 'test-token',
    ]);
    $response = $app->handle($request);
    echo "Status: " . $response->getStatusCode() . "<br>";
    echo "Redirect: " . ($response->isRedirect() ? 'YES' : 'NO') . "<br>";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
}
