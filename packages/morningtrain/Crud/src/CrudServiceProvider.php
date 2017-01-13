<?php

namespace morningtrain\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use morningtrain\Crud\Commands\NewCrud;
use morningtrain\Crud\Services\Crud;

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
        // Register commands
        $this->commands([
            NewCrud::class
        ]);

        // Register service
        $this->app->singleton('crud', function() {
            return new Crud();
        });
    }

    public function registerBaseClasses() {
        $janitor = $this->app->make('janitor');
        $baseModel = config('crud.base-classes.model', Model::class);
        $baseController = config('crud.base-classes.controller', Controller::class);

        $janitor->registerClasses([
            'Model'         => $baseModel,
            'Controller'    => $baseController

        ], '\\morningtrain\\Crud\\Base\\');
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
            __DIR__ . '/../resources/views/crud' => base_path('resources/views/crud')

        ], 'views');

        // Publish fields
        $this->publishes([
            __DIR__ . '/../resources/fields' => base_path('resources/fields')

        ], 'fields');

        // Publish stubs
        $this->publishes([
            __DIR__ . '/../resources/stubs' => base_path('resources/stubs')

        ], 'stubs');

        // Publish lang
        $this->publishes([
            __DIR__ . '/../resources/lang'  => base_path('resources/lang')

        ], 'lang');

    }

}