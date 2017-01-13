<?php

namespace App\Models;

use morningtrain\Crud\Contracts\Model;


class Post extends Model {
    

    
    
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