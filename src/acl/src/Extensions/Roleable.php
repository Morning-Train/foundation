<?php

namespace morningtrain\Acl\Extensions;

use morningtrain\Acl\Models\Permission;
use morningtrain\Acl\Models\Role;
use morningtrain\Janitor\Services\Janitor;

trait Roleable
{
    use Permissionable;

    /*
     * Relationships
     */

    public function roles()
    {
        return $this->morphToMany(app()->make(Janitor::class)->getPublishedModelFor(Role::class), 'roleable');
    }

    /*
     * Helpers
     */

    public function allowed($permission)
    {
        if ($permission instanceof Permission) {
            $permission = $permission->slug;
        }

        $permissionables = ['roles'];

        // Append new permissionables if set
        if (isset($this->permissionables) && is_array($this->permissionables)) {
            $permissionables = array_merge($permissionables, $this->permissionables);
        }

        return $this->newQuery()->where('id', $this->id)->where(function ($query) use ($permission, $permissionables) {

                // Check assigned permissions
                $query->whereHas('permissions', function ($query) use ($permission) {
                    return $query->where('slug', $permission);
                });

                // Check permissionables
                foreach ($permissionables as $relation) {
                    $query->orWhereHas($relation, function ($query) use ($permission) {
                        return $query->where('is_super', 1)->orWhereHas('permissions',
                                function ($query) use ($permission) {
                                    $query->where('slug', $permission);
                                });
                    });
                }

                return $query;

            })->count() > 0;
    }

    public function isSuper()
    {
        return $this->roles()->whereIsSuper()->count() > 0;
    }

    public function assign(array $roles)
    {
        foreach ($roles as $role) {
            if (!$role instanceof Role) {
                $role = Role::where('slug', $role)->first();
            }

            if (!is_null($role)) {
                $this->roles()->attach($role->id);
            }
        }
    }

    public function unassign(array $roles)
    {
        foreach ($roles as $role) {
            if (!$role instanceof Role) {
                $role = Role::where('slug', $role)->first();
            }

            if (!is_null($role)) {
                $this->roles()->detach($role->id);
            }
        }
    }

    public function isAssigned(array $roles)
    {
        $query = $this->newQuery()->where('id', $this->id);

        foreach ($roles as $role) {
            $slug = $role instanceof Role ? $role->slug : $role;

            $query->whereHas('roles', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        }

        return $query->count() > 0;
    }

}