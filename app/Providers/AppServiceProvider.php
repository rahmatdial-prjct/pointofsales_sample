<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom Blade Directives
        Blade::if('director', function () {
            return Auth::check() && Auth::user()->isDirector();
        });

        Blade::if('manager', function () {
            return Auth::check() && Auth::user()->isManager();
        });

        Blade::if('employee', function () {
            return Auth::check() && Auth::user()->isEmployee();
        });

        // Register custom middleware alias
        // Route::middleware(['role' => CheckRole::class]);
        // Route::middleware(['branch.access' => CheckBranchAccess::class]);
    }
}
