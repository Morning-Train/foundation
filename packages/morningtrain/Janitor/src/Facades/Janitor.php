<?php

namespace morningtrain\Janitor\Facades;

use Illuminate\Support\Facades\Facade;
use morningtrain\Janitor\Services\Janitor as JanitorService;

class Janitor extends Facade  {

    protected static function getFacadeAccessor() {
        return JanitorService::class;
    }

}