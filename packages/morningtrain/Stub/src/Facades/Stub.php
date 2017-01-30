<?php

namespace morningtrain\Stub\Facades;

use Illuminate\Support\Facades\Facade;

class Stub extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'stub';
    }

}