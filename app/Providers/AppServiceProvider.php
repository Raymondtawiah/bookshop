<?php

namespace App\Providers;

use App\Contracts\FCMNotificationInterface;
use App\Contracts\FirebaseAuthInterface;
use App\Http\Middleware\CheckWebinarAccess;
use App\Services\CartService;
use App\Services\FCMNotificationService;
use App\Services\FirebaseAuthService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService;
        });

        $this->app->singleton(FirebaseAuthInterface::class, function ($app) {
            return new FirebaseAuthService;
        });

        $this->app->singleton(FCMNotificationInterface::class, function ($app) {
            return new FCMNotificationService(
                $app->make(FirebaseAuthInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Register middleware alias
        Route::aliasMiddleware('check.webinar.access', CheckWebinarAccess::class);

        // Register gates
        Gate::define('view-webinar-admin', function ($user, $webinar) {
            return $user->is_admin;
        });

        Gate::define('update-webinar', function ($user, $webinar) {
            return $user->is_admin || $webinar->created_by === $user->id;
        });

        Gate::define('delete-webinar', function ($user, $webinar) {
            return $user->is_admin;
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
