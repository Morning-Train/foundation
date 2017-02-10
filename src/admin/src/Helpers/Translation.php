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

    public static function getCrudBasepath($model)
    {
        $slug = (new $model)->getPluralName();
        $adminPrefix = config('janitor.routing.groups.admin.prefix', trans('admin.prefix'));
        $modelPrefix = static::get("crud.$slug.prefix", [], $slug);

        return "$adminPrefix/$modelPrefix";
    }

}