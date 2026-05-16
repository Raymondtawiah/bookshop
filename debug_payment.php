<?php

// Simple test to see what the payment initialize endpoint returns
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->boot();

// Enable error reporting to catch any issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test the exact scenario from the frontend
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'payment_method' => 'card', // This will be overridden by our auto-selection
    'email' => 'test@example.com',
    'contact' => '0551234567',
    'customer_name' => 'Test Customer',
    'residence' => 'Test Residence',
    'nationality' => 'USA' // This should trigger Stripe
];

// Mock auth to simulate no logged in user (so email is required and provided)
$authMock = Mockery::mock('Illuminate\Contracts\Auth\Factory');
$authMock->shouldReceive('check')->andReturn(false);
$app->instance('auth', $authMock);

// Also mock the session
$sessionMock = Mockery::mock('Illuminate\Session\Store');
$app->instance('session', $sessionMock);

// Test the controller
try {
    $controller = $app->make(App\Http\Controllers\PaymentController::class);
    
    // Create request
    $request = Illuminate\Http\Request::capture();
    
    // Start output buffering to capture any accidental output
    ob_start();
    
    // Call the method
    $result = $controller->initializePayment($request);
    
    // Get any output that was accidentally sent
    $output = ob_get_clean();
    
    if ($output !== '') {
        echo "ACCIDENTAL OUTPUT DETECTED (this would cause JSON parsing error):\n";
        echo "--- START OUTPUT ---\n";
        echo $output;
        echo "\n--- END OUTPUT ---\n";
        echo "Length: " . strlen($output) . " bytes\n";
        echo "First 100 chars: '" . substr($output, 0, 100) . "'\n";
    } else {
        echo "No accidental output detected.\n";
    }
    
    // Check what we got back
    echo "Result type: " . gettype($result) . "\n";
    
    if ($result instanceof Illuminate\Http\JsonResponse) {
        echo "SUCCESS: Got JsonResponse\n";
        $data = $result->getData(true);
        echo "Response data:\n";
        print_r($data);
    } elseif ($result instanceof Symfony\Component\HttpFoundation\RedirectResponse) {
        echo "WARNING: Got RedirectResponse (this would cause HTML output):\n";
        echo "Redirect URL: " . $result->getTargetUrl() . "\n";
    } elseif ($result instanceof Illuminate\Http\RedirectResponse) {
        echo "WARNING: Got Illuminate RedirectResponse (this would cause HTML output):\n";
        echo "Redirect URL: " . $result->getTargetUrl() . "\n";
    } else {
        echo "Got other result type:\n";
        var_dump($result);
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

// Also test the router directly
echo "\n" . "="*50 . "\n";
echo "Testing PaymentRouterService directly:\n";

try {
    $router = $app->make(App\Services\Payments\PaymentRouterService::class);
    $result = $router->routePayment(
        'test@example.com',
        70.0,
        'TEST-REF',
        'USA',
        'http://example.com/success',
        'http://example.com/cancel'
    );
    
    echo "Router result:\n";
    print_r($result);
} catch (Exception $e) {
    echo "Router error: " . $e->getMessage() . "\n";
}