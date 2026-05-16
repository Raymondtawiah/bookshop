<?php

require __DIR__ . '/vendor/autoload.php';

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

 // Test config helper
 try {
     $appName = config('app.name');
     echo "Config helper works: " . $appName . "\n";
 } catch (Exception $e) {
     echo "Config helper failed: " . $e->getMessage() . "\n";
 }

// Test services
try {
    $paystack = $app->make(App\Services\PaystackService::class);
    echo "✓ Paystack service created successfully\n";
} catch (Exception $e) {
    echo "✗ Error creating Paystack service: " . $e->getMessage() . "\n";
}

try {
    $stripe = $app->make(App\Services\StripeService::class);
    echo "✓ Stripe service created successfully\n";
} catch (Exception $e) {
    echo "✗ Error creating Stripe service: " . $e->getMessage() . "\n";
}

try {
    $router = $app->make(App\Services\Payments\PaymentRouterService::class);
    echo "✓ PaymentRouterService created successfully\n";
} catch (Exception $e) {
    echo "✗ Error creating PaymentRouterService: " . $e->getMessage() . "\n";
}