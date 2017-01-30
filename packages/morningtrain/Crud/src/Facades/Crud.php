<?php

namespace morningtrain\Crud\Facades;

use Illuminate\Support\Facades\Facade;
use morningtrain\Crud\Services\Crud as CrudService;

class Crud extends Facade
{

    protected static function getFacadeAccessor()
    {
        return CrudService::class;
    }

}