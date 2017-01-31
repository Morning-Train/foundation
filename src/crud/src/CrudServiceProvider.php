<?php

namespace morningtrain\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use morningtrain\Crud\Commands\NewCrud;
use morningtrain\Crud\Services\Crud;

class CrudServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Publish files
        $this->publish();

        // Register commands
        $this->commands([
            NewCrud::class,
        ]);

        // Register service
        $this->app->singleton(Crud::class, function ($app) {
            return new Crud($app->make(Router::class));
        });
    }

    /**
     * Files to publish
     */
    public function publish()
    {

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/crud.php' => config_path('crud.php'),

        ], 'config');

        // Publish gulp file
        $this->publishes([
            __DIR__ . '/../gulp/fields.js' => base_path('gulp/fields.js'),

        ], 'gulp');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views/crud' => base_path('resources/views/crud'),

        ], 'views');

        // Publish fields
        $this->publishes([
            __DIR__ . '/../resources/fields' => base_path('resources/fields'),

        ], 'fields');

        // Publish stubs
        $this->publishes([
            __DIR__ . '/../resources/stubs' => base_path('resources/stubs'),

        ], 'stubs');

        // Publish lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang'),

        ], 'lang');

    }

}