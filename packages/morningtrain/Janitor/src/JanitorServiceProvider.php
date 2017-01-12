<?php

namespace morningtrain\Janitor;

use Illuminate\Support\ServiceProvider;
use morningtrain\Janitor\Services\Janitor;

class JanitorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/janitor.php'  => config_path('janitor.php')

        ], 'config');

        // Register service
        $this->app->singleton('janitor', function () {
            return new Janitor();
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}