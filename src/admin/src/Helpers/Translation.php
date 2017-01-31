<?php

namespace morningtrain\Admin\Helpers;

class Translation
{

    public static function get(string $key, array $parameters = [], $default = null)
    {
        $trans = trans($key, $parameters);

        if (is_null($default)) {
            $default = $trans;
        }

        return $key === $trans ? $default : $trans;
    }

}