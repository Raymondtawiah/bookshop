<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Testing Full Login Flow ===<br><br>";

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Step 1: Visit login page to get session and CSRF token
echo "Step 1: GET /login<br>";
$request1 = Illuminate\Http\Request::create('/login', 'GET');
$response1 = $kernel->handle($request1);
echo "Status: " . $response1->getStatusCode() . "<br>";

// Extract CSRF token from response
$content = $response1->getContent();
preg_match('/name="_token"[^>]*value="([^"]+)"/', $content, $matches);
$csrfToken = $matches[1] ?? null;
echo "CSRF Token found: " . ($csrfToken ? 'YES' : 'NO') . "<br>";
if ($csrfToken) {
    echo "Token: " . substr($csrfToken, 0, 20) . "...<br>";
}

// Get session ID from cookie
$cookies = $response1->headers->getCookies();
echo "Cookies set: " . count($cookies) . "<br>";
foreach ($cookies as $cookie) {
    echo "- " . $cookie->getName() . "<br>";
}

$kernel->terminate($request1, $response1);

echo "<br>Step 2: POST /login with CSRF token<br>";
// Step 2: Submit login form with CSRF token
$request2 = Illuminate\Http\Request::create('/login', 'POST', [
    'email' => 'admin@example.com',
    'password' => 'password',
    '_token' => $csrfToken,
]);

// Copy cookies from first request
foreach ($cookies as $cookie) {
    $request2->cookies->set($cookie->getName(), $cookie->getValue());
}

$response2 = $kernel->handle($request2);
echo "Status: " . $response2->getStatusCode() . "<br>";

if ($response2->isRedirect()) {
    echo "✅ Redirects to: " . $response2->headers->get('Location') . "<br>";
} else {
    echo "Content: " . substr($response2->getContent(), 0, 300) . "<br>";
}
