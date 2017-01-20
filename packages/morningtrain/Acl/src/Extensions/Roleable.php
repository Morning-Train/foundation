<?php

namespace morningtrain\Acl\Extensions;

use morningtrain\Acl\Models\Permission;
use morningtrain\Acl\Models\Role;
use morningtrain\Janitor\Services\Janitor;

trait Roleable {
    use Permissionable;

    /*
     * Relationships
     */

    public function roles() {
        return $this->morphToMany(app()->make(Janitor::class)->getPublishedModelFor(Role::class), 'roleable');
    }

    /*
     * Helpers
     */

    public function allowed( $permission ) {
        if ($permission instanceof Permission) {
            $permission = $permission->slug;
        }

        $permissionables = [ 'roles' ];

        // Append new permissionables if set
        if (isset($this->permissionables) && is_array($this->permissionables)) {
            $permissionables = array_merge($permissionables, $this->permissionables);
        }

        return $this->newQuery()
            ->where('id', $this->id)
            ->where(function( $query ) use( $permission, $permissionables ) {

                // Check assigned permissions
                $query->whereHas('permissions', function( $query ) use( $permission ) {
                    return $query->where('slug', $permission);
                });

                // Check permissionables
                foreach( $permissionables as $relation ) {
                    $query->orWhereHas($relation, function( $query ) use( $permission ) {
                        return $query
                            ->where('is_super', 1)
                            ->orWhereHas('permissions', function( $query ) use ( $permission ) {
                                $query->where('slug', $permission);
                            });
                    });
                }

                return $query;

            })->count() > 0;
    }

}