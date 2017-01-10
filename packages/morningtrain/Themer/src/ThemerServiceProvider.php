<?php

namespace morningtrain\Themer;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use morningtrain\Themer\Middleware\LoadTheme;
use morningtrain\Themer\Services\Themer;

class ThemerServiceProvider extends ServiceProvider
{

    public function publish() {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/themer.php'  => config_path('themer.php')

        ], 'config');

        // Publish gulp file
        $this->publishes([
            __DIR__ . '/../gulp/themer.js'  => base_path('gulp/themer.js')

        ], 'gulp');
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publish();

        // Register service
        $this->app->bind('Themer', function( $app ) {
            // Register middleware in Janitor
            $app->make('Janitor')->registerMiddleware([
                LoadTheme::class
            ]);

            return new Themer();
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