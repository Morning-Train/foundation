<?php

namespace morningtrain\Acl;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use morningtrain\Acl\Middleware\HasAccess;
use morningtrain\Acl\Middleware\HasPermissions;
use morningtrain\Acl\Models\Permission;
use morningtrain\Acl\Models\Role;
use morningtrain\Janitor\Contracts\JanitorFeature;
use morningtrain\Janitor\Helper\MigrationHelper;

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
        'auth.can'    => HasPermissions::class,
    ];

    /*
     * Publisher
     */

    protected function publisher()
    {
        return function () {
            $artisan = app()->make(ConsoleKernel::class);

            // Migrate
            $artisan->call('migrate');

            // Call artisan acl:build
            $artisan->call('acl:build');
        };
    }

}