<?php

namespace morningtrain\Acl\Extensions;

use morningtrain\Acl\Models\Permission;
use morningtrain\Janitor\Services\Janitor;

trait Permissionable {

    /*
     * Relationships
     */

    public function permissions() {
        return $this->morphToMany(app()->make(Janitor::class)->getPublishedModelFor(Permission::class), 'permissionable');
    }

    /*
     * Helpers
     */

    public function allowed( $permission ) {
        if ($permission instanceof Permission) {
            $permission = $permission->slug;
        }

        return $this->permissions()->where('slug', $permission)->count() > 0;
    }

    public function grant( $permission ) {
        if (!$permission instanceof Permission) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if (!is_null($permission)) {
            $this->permissions()->attach($permission->id);
        }
    }

    public function refuse( $permission ) {
        if (!$permission instanceof Permission) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if (!is_null($permission)) {
            $this->permissions()->detach($permission->id);
        }
    }

}