<?php

namespace morningtrain\Acl;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use morningtrain\Acl\Commands\Build;
use morningtrain\Acl\Commands\Seed;
use morningtrain\Crud\Services\Crud;
use morningtrain\Janitor\Services\Janitor;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Gate $gate, Janitor $janitor)
    {
        // Register janitor feature
        $janitor->provide([
            AclFeature::class,
        ]);

        // Patch the gate
        $gate->before(function ($user, $permission, $entities) {

            // Check for permission if no entities are passed
            // (case in which the gate will resolve the policy)

            if (!is_array($entities) || (count($entities) === 0)) {
                if ($user->allowed($permission)) {
                    return true;
                }
            }
        });
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
            Build::class,
            Seed::class,
        ]);

        // Crud access
        $this->registerCrudAccessFilter();
    }

    /*
     * Publish files
     */

    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../config/acl.php' => base_path('config/acl.php'),

        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/stubs/acl' => base_path('resources/stubs/acl'),

        ], 'stubs');
    }

    /*
     * ACL Access
     */

    protected function registerCrudAccessFilter()
    {
        // Register acl access filter if the service is loaded
        if (class_exists('\morningtrain\Crud\CrudServiceProvider')) {
            $this->app->make('\morningtrain\Crud\Services\Crud')->addFilter(function ($model, $options) {

                $middleware = isset($options['middleware']) ? $options['middleware'] : null;

                if (is_string($middleware)) {
                    $middleware = [$middleware];
                } else {
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