<?php

namespace Movor\LaravelModelMeta;

use Illuminate\Support\ServiceProvider;

class LaravelModelMetaServiceProvider extends ServiceProvider
{
    public $packageName = 'laravel-model-meta';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
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