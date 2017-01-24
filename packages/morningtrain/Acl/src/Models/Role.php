<?php

namespace morningtrain\Acl\Models;

use morningtrain\Acl\Extensions\HasDisplayName;
use morningtrain\Acl\Extensions\Permissionable;
use morningtrain\Crud\Contracts\Model;

class Role extends Model {
    use HasDisplayName,
        Permissionable;

    /*
     * Settings
     */

    protected $appends = [
        'display_name'
    ];

    /*
     * Scopes
     */

    public function scopeWhereIsSuper( $query ) {
        return $query->where('is_super', 1);
    }
    
}