<?php

namespace App\Models;

use morningtrain\Acl\Extensions\Roleable;
use morningtrain\Crud\Contracts\Model;


class Company extends Model {
    use Roleable;
    
    
    
    /**
    * Dates
    *
    * @var  array
    */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

}