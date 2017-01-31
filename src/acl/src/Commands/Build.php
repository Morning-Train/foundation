<?php

namespace morningtrain\Acl\Commands;

use Illuminate\Console\Command;
use morningtrain\Acl\Models\Permission;
use morningtrain\Acl\Models\Role;
use morningtrain\Janitor\Services\Janitor;
use Symfony\Component\Console\Input\InputArgument;

class Build extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs the database with the acl config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Janitor $janitor)
    {
        parent::__construct();

        $this->permissionModel = $janitor->getPublishedModelFor(Permission::class);
        $this->roleModel = $janitor->getPublishedModelFor(Role::class);
    }

    /*
     * Model classes
     *
     */

    /**
     * @var string
     */
    protected $permissionModel;

    /**
     * @var string
     */
    protected $roleModel;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Recache config
        $this->call('config:cache');

        $this->syncPermissions(config('acl.permissions', []));
        $this->syncRoles(config('acl.roles', []));

        $this->info('ACL has been built.');
    }

    protected function syncPermissions(array $permissions, string $namespace = '')
    {
        $permissionModel = $this->permissionModel;
        $roleModel = $this->roleModel;

        foreach ($permissions as $segment => $name) {
            // Nested permissions
            if (is_array($name)) {
                $this->syncPermissions($name, strlen($namespace) > 0 ? "$namespace.$segment" : $segment);
                continue;
            }

            // Non-named permission
            if (is_int($segment)) {
                $segment = $name;
                $name = null;
            }

            // Prepare data
            $slug = strlen($namespace) > 0 ? "$namespace.$segment" : $segment;

            // Check if it needs update
            $permission = $permissionModel::where('slug', $slug)->first();

            // Create the permission if missing
            if (is_null($permission)) {
                $permission = new $permissionModel();
                $permission->slug = $slug;
                $permission->name = $name;
                $permission->save();
            } // Check if the name needs update
            else {
                if ($permission->name !== $name) {
                    $permission->name = $name;
                    $permission->save();
                }
            }
        }
    }

    protected function syncRoles(array $roles)
    {
        $permissionModel = $this->permissionModel;
        $roleModel = $this->roleModel;

        foreach ($roles as $slug => $data) {
            // Check if it needs update
            $role = $roleModel::where('slug', $slug)->first();
            $name = isset($data['name']) ? $data['name'] : null;
            $super = isset($data['super']) && $data['super'] ? 1 : 0;
            $permissions = isset($data['permissions']) && is_array($data['permissions']) ? $data['permissions'] : [];

            // Create the role if missing
            if (is_null($role)) {
                $role = new $roleModel();
                $role->slug = $slug;
                $role->name = $name;
                $role->is_super = $super;
                $role->save();
            } // Check if the fields need update
            else {
                if (($role->name !== $name) || ($role->is_super !== $super)) {
                    $role->name = $name;
                    $role->is_super = $super;
                    $role->save();
                }
            }

            // Check if any permissions needs to be refused
            $currentPermissions = $role->permissions;

            foreach ($currentPermissions as $permission) {
                if (!in_array($permission->slug, $permissions)) {
                    $role->refuse($permission);
                }
            }

            // Grant new permissions
            foreach ($permissions as $permission) {
                if (is_null($currentPermissions->where('slug', $permission)->first())) {
                    $role->grant($permission);
                }
            }
        }
    }
}
