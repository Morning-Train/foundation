<?php

namespace morningtrain\Themer\Facades;

use Illuminate\Support\Facades\Facade;
use morningtrain\Themer\Services\Themer as ThemerService;

class Theme extends Facade {
    protected static function getFacadeAccessor() {
        return ThemerService::class;
    }
}