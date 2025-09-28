<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SaasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom([
            database_path('migrations'),
            database_path('migrations/Saas'),
        ]);
    }
}
