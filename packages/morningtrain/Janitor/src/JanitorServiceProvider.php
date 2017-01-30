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
    public function boot()
    {
        // Register initializer to publish gulpfile
        $this->app->make(Janitor::class)->registerInitializer(function () {
            $path = base_path('gulpfile.js');

            if (file_exists($path)) {
                unlink($path);
            }

            copy(__DIR__ . '/../gulpfile/gulpfile.js', $path);
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

        // Publish config file
        $this->publish();

        // Register commands
        $this->commands([
            Publish::class,
        ]);

        // Register service
        $this->app->singleton(Janitor::class, function ($app) {
            return new Janitor($app->make(Router::class));
        });
    }

    /*
     * Publish files
     */

    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../config/janitor.php' => config_path('janitor.php'),

        ], 'config');
    }
}