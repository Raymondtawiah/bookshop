<?php

// Test the PaymentController directly
require 'vendor/autoload.php';

// Bootstrap Laravel with all necessary providers
$app = new Illuminate\Foundation\Application(
    $_SERVER['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Register essential service providers
$app->register(App\Providers\AppServiceProvider::class);
$app->register(Illuminate\Foundation\Providers\ArtisanServiceProvider::class);
$app->register(Illuminate\Foundation\Providers\AuthHelperServiceProvider::class);
$app->register(Illuminate\Foundation\Providers\EventServiceProvider::class);
$app->register(Illuminate\Routing\RoutingServiceProvider::class);

// Boot the application
$app->boot();

// Set up test request data matching what the frontend sends
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'payment_method' => 'card', // Will be overridden by auto-selection
    'email' => 'test@example.com',
    'contact' => '0551234567',
    'customer_name' => 'Test Customer',
    'residence' => 'Test Residence',
    'nationality' => 'USA'
];

// Mock authentication (no logged in user, so email is required and provided)
$authMock = Mockery::mock('Illuminate\Contracts\Auth\Factory');
$authMock->shouldReceive('check')->andReturn(false);
$app->instance('auth', $authMock);

// Mock session
$sessionMock = Mockery::mock('Illuminate\Session\Store');
$app->instance('session', $sessionMock);

try {
    // Create the controller
    $controller = $app->make(App\Http\Controllers\PaymentController::class);
    echo "Controller created successfully\n";
    
    // Create request object from globals
    $request = Illuminate\Http\Request::capture();
    
    // Capture any output (to detect accidental HTML/whitespace)
    ob_start();
    
    // Call the method
    $result = $controller->initializePayment($request);
    
    // Get any output that was accidentally sent
    $output = ob_get_clean();
    
    if ($output !== '') {
        echo "ACCIDENTAL OUTPUT DETECTED:\n";
        echo "--- START ---\n";
        var_dump($output);
        echo "--- END ---\n";
    } else {
        echo "No accidental output detected.\n";
    }
    
    // Check what we got back
    echo "Result type: " . gettype($result) . "\n";
    
    if ($result instanceof Illuminate\Http\JsonResponse) {
        echo "SUCCESS: JsonResponse received\n";
        $data = $result->getData(true);
        echo "Response data:\n";
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    } elseif ($result instanceof Symfony\Component\HttpFoundation\RedirectResponse) {
        echo "WARNING: RedirectResponse (would cause HTML output):\n";
        echo "Redirect URL: " . $result->getTargetUrl() . "\n";
    } else {
        echo "Other result type:\n";
        var_dump($result);
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n";
    echo $e->getTraceAsString() . "\n";
}

// Also test the PaymentRouterService directly
echo "\n" . "="*50 . "\n";
echo "Testing PaymentRouterService:\n";

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
    
    echo "Router test result:\n";
    echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "Router error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}