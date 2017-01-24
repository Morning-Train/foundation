<?php

namespace morningtrain\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use morningtrain\Crud\Commands\NewCrud;
use morningtrain\Crud\Services\Crud;

class CrudServiceProvider extends ServiceProvider {

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot( Router $router ) {

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
            NewCrud::class
        ]);

        // Register service
        $this->app->singleton(Crud::class, function( $app ) {
            return new Crud($app->make(Router::class));
        });

        // Register extensions
        $this->registerAclAccess();
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

    /*
     * ACL Access
     */

    protected function registerAclAccess() {
        // Register acl access filter if the service is loaded
        if (class_exists('\morningtrain\Acl\AclServiceProvider')) {
            $this->app->make(Crud::class)->addFilter(function( $model, $options ) {

                $middleware = isset($options['middleware']) ? $options['middleware'] : null;

                if (is_string($middleware)) {
                    $middleware = [ $middleware ];
                }
                else {
                    $middleware = [];
                }

                $public = isset($options['public']) && ($options['public'] === true) ? true : false;

                if (($public === false) && !in_array('auth.access', $middleware)) {
                    $middleware[] = 'auth.access';
                }

                unset($options['public']);

                if (count($middleware) > 0) {
                    $options['middleware'] = $middleware;
                }

                return $options;

            });
        }
    }

}