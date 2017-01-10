<?php

namespace morningtrain\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider {

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
        $this->publish();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Files to publish
     */
    public function publish() {

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/crud.php'  => config_path('crud.php')

        ], 'config');

        // Publish gulp file
        $this->publishes([
            __DIR__ . '/../gulp/fields.js'  => base_path('gulp/fields.js')

        ], 'gulp');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/crud')

        ], 'views');

    }

}