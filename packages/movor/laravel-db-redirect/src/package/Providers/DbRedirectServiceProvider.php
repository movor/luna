<?php

namespace Movor\LaravelDbRedirect\Providers;

use Illuminate\Support\ServiceProvider;
use Movor\LaravelDbRedirect\DbRedirectHandler;

class DbRedirectServiceProvider extends ServiceProvider
{
    public static $packageName = 'laravel-db-redirect';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(static::$packageName, DbRedirectHandler::class);
    }
}