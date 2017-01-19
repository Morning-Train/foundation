<?php

namespace morningtrain\Acl\Models;

use morningtrain\Acl\Extensions\HasDisplayName;
use morningtrain\Crud\Contracts\Model;

class Permission extends Model {
    use HasDisplayName;

    /*
     * Settings
     */

    protected $appends = [
        'display_name'
    ];

}