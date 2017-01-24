<?php

namespace morningtrain\Admin;

use Illuminate\Support\ServiceProvider;
use morningtrain\Admin\Commands\Update;
use morningtrain\Admin\Features\AdminFeature;
use morningtrain\Admin\Features\AuthFeature;
use morningtrain\Janitor\Services\Janitor;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot( Janitor $janitor ) {
        // Register features
        $janitor->provide([
            AuthFeature::class,
            AdminFeature::class
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        // Publish files
        $this->publish();

        // Register commands
        $this->commands([
            Update::class
        ]);
    }

    /**
     * Files to publish
     */
    public function publish() {

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/admin.php'  => config_path('admin.php')

        ], 'config');

        // Publish lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang')

        ], 'language');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views')

        ], 'views');

        // Publish themes
        $this->publishes([
            __DIR__ . '/../resources/themes' => base_path('resources/themes')

        ], 'themes');

    }
}