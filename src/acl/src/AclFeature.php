<?php

namespace morningtrain\Acl;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use morningtrain\Acl\Middleware\HasAccess;
use morningtrain\Acl\Middleware\HasPermissions;
use morningtrain\Acl\Middleware\IsAssigned;
use morningtrain\Acl\Middleware\RequireAuthentication;
use morningtrain\Acl\Models\Permission;
use morningtrain\Acl\Models\Role;
use morningtrain\Janitor\Contracts\JanitorFeature;
use morningtrain\Janitor\Helper\MigrationHelper;
use morningtrain\Stub\Services\Stub;
use morningtrain\Acl\Models\User;
use morningtrain\Acl\Extensions\Roleable;

class AclFeature extends JanitorFeature
{

    protected $migrations = [
        __DIR__ . '/Migrations/create_permissions_table.php',
        __DIR__ . '/Migrations/create_roles_table.php',
        __DIR__ . '/Migrations/create_permissionables_table.php',
        __DIR__ . '/Migrations/create_roleables_table.php',
    ];

    protected $models = [
        Permission::class,
        Role::class,
    ];

    protected $middleware = [
        'auth.access' => HasAccess::class,
        'auth.can' => HasPermissions::class,
        'auth.require' => RequireAuthentication::class,
        'auth.is' => IsAssigned::class
    ];

    /*
     * Initializer
     */

    protected function initializer()
    {
        return function () {
            $modelsPath = config('janitor.paths.models', app_path('Models'));
            $destination = $modelsPath . '/User.php';

            if (!file_exists($destination)) {
                app()->make(Stub::class)->create('acl/user', $destination, [
                    'namespace' => config('janitor.namespaces.models', 'App\\Http\\Models'),
                    'class' => 'User',
                    'imports' => [
                        User::class => 'Authenticatable',
                        Roleable::class
                    ],
                    'extends' => 'Authenticatable',
                    'uses' => [
                        Roleable::class
                    ]
                ]);
            }
        };
    }

    /*
     * Publisher
     */

    protected function publisher()
    {
        return function () {

            /*
             * Migrate and build
             */

            $artisan = app()->make(ConsoleKernel::class);

            // Migrate
            $artisan->call('migrate');

            // Call artisan acl:build
            $artisan->call('acl:build');
        };
    }

}