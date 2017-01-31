<?php

namespace morningtrain\Janitor\Facades;

use Illuminate\Support\Facades\Facade;

class Janitor extends Facade  {

    protected static function getFacadeAccessor() {
        return 'Janitor';
    }

}