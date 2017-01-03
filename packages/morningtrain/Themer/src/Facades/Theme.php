<?php

namespace morningtrain\Themer\Facades;

use Illuminate\Support\Facades\Facade;

class Theme extends Facade {
    protected static function getFacadeAccessor() {
        return 'Themer';
    }
}