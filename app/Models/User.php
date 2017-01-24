<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use morningtrain\Acl\Models\User as Authenticatable;
use morningtrain\Acl\Extensions\Roleable;
use morningtrain\Admin\Extensions\HasAvatar;

class User extends Authenticatable
{
    use Notifiable,
        Roleable,
        HasAvatar;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Extra permissionables relationships (besides roles)
     *
     * @var array
     */
    protected $permissionables = [
        'companies.roles'
    ];

    /*
     * Relationships
     */

    public function companies() {
        return $this->belongsToMany(Company::class, 'user_company_relation');
    }
}
