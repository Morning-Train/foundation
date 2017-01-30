<?php

namespace App\Models;

use morningtrain\Crud\Contracts\Model;


class Report extends Model
{

    /**
     * Dates
     *
     * @var  array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /*
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}