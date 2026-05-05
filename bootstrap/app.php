<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckUserActivity;
use App\Http\Middleware\ProtectWebinarLink;
use App\Http\Middleware\ValidateWebinarAccessToken;
use App\Http\Middleware\VerifyCustomerEmail;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'verify.customer' => VerifyCustomerEmail::class,
            'user.activity' => CheckUserActivity::class,
            'webinar.access.token' => ValidateWebinarAccessToken::class,
            'protect.webinar.link' => ProtectWebinarLink::class,
        ]);

        $middleware->web(append: [
            CheckUserActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
