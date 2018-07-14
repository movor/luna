<?php

namespace Movor\LaravelMeta;

use Illuminate\Support\ServiceProvider;

class LaravelMetaServiceProvider extends ServiceProvider
{
    public $packageName = 'laravel-meta';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->mergeConfigFrom(__DIR__ . "/../config/{$this->packageName}.php", $this->packageName);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
