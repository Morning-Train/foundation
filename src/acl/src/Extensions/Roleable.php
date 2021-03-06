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
     * Scopes
     */

    public function scopeWhereIsSuper($query)
    {
        return $query->whereHas('roles', function ($query) {
            return $query->whereIsSuper();
        });
    }

    public function scopeWhereIsAssigned($query, array $roles)
    {
        foreach ($roles as $role) {
            $slug = $role instanceof Role ? $role->slug : $role;

            $query->whereHas('roles', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        }

        return $query;
    }

    public function scopeWhereIsAssignedEither($query, array $roles)
    {
        return $query->where(function ($query) use ($roles) {
            foreach ($roles as $role) {
                $slug = $role instanceof Role ? $role->slug : $role;

                $query->orWhereHas('roles', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                });
            }
        });
    }

    public function scopeWhereIsAllowed($query, $permission)
    {

        if ($permission instanceof Permission) {
            $permission = $permission->slug;
        }

        $permissionables = ['roles'];

        // Append new permissionables if set
        if (isset($this->permissionables) && is_array($this->permissionables)) {
            $permissionables = array_merge($permissionables, $this->permissionables);
        }

        return $query->where(function ($query) use ($permission, $permissionables) {

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

        });
    }

    public function scopeWhereHasAccess($query, $slug)
    {
        return $this->scopeWhereIsAllowed($query, "access.$slug");
    }

    /*
     * Helpers
     */

    public function allowed($permission)
    {
        return $this->newQuery()
            ->where('id', $this->id)
            ->whereIsAllowed($permission)
            ->count() > 0;
    }

    public function hasAccess($slug)
    {
        return $this->allowed("access.$slug");
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

            if (!is_null($role) && ($this->roles()->where('id', $role->id)->count() === 0)) {
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
        return $this->newQuery()
            ->where('id', $this->id)
            ->whereIsAssigned($roles)
            ->count() > 0;
    }

    public function isAssignedEither(array $roles)
    {
        return $this->newQuery()
            ->where('id', $this->id)
            ->whereIsAssignedEither($roles)
            ->count() > 0;
    }

}