<?php

namespace SmallRuralDog\LaravelCustom;

use Illuminate\Support\ServiceProvider;

class LaravelCustomServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__ . '/Config/laravel-custom.php' => config_path('laravel-custom.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/laravel-custom.php','laravel-custom');

    }
}
