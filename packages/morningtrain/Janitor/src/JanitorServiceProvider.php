<?php

namespace morningtrain\Janitor;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use morningtrain\Janitor\Commands\Publish;
use morningtrain\Janitor\Services\Janitor;

class JanitorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/janitor.php'  => config_path('janitor.php')

        ], 'config');

        // Register service
        $this->app->singleton('janitor', function ( $app ) {
            return new Janitor($app->make(Router::class));
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        $this->commands([
            Publish::class
        ]);
    }
}