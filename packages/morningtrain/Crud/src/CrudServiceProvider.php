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

        // Base classes
        $this->registerBaseClasses();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {

    }

    public function registerBaseClasses() {
        $janitor = $this->app->make('janitor');
        $modelNamespace = config('janitor.namespaces.models', 'App\Models');
        $controllerNamespace = config('janitor.namespaces.controllers', 'App\Http\Controllers');

        // Base model
        if (!class_exists("$modelNamespace\\Model")) {
            $janitor->registerModels([
                 "$modelNamespace\\Model"   => \Illuminate\Database\Eloquent\Model::class
            ]);
        }

        // Base controller
        if (!class_exists("$controllerNamespace\\Controller")) {
            $janitor->registerControllers([
                "$controllerNamespace\\Controller"  => \Illuminate\Routing\Controller::class
            ]);
        }

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

        // Publish fields
        $this->publishes([
            __DIR__ . '/../resources/fields' => base_path('resources/fields')

        ], 'fields');

        // Publish stubs
        $this->publishes([
            __DIR__ . '/../resources/stubs' => base_path('resources/stubs')

        ], 'stubs');

    }

}