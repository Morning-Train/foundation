<?php

namespace morningtrain\Acl;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use morningtrain\Acl\Commands\Build;
use morningtrain\Acl\Commands\Seed;
use morningtrain\Janitor\Services\Janitor;

class AclServiceProvider extends ServiceProvider {
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot( Gate $gate, Janitor $janitor ) {
        // Register janitor feature
        $janitor->provide([
            AclFeature::class
        ]);

        // Patch the gate
        $gate->before(function( $user, $permission, $entities ) {

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
    public function register() {
        // Publish files
        $this->publish();

        // Register commands
        $this->commands([
            Build::class,
            Seed::class
        ]);
    }

    /*
     * Publish files
     */

    protected function publish() {
        $this->publishes([
            __DIR__ . '/../config/acl.php'  => base_path('config/acl.php')

        ], 'config');
    }
}