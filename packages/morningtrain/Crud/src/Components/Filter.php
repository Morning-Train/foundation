<?php

namespace morningtrain\Crud\Components;

use Illuminate\Support\Collection;

abstract class Filter {

    public static function order( Collection $columns ) {
        return function($query, $name) use($columns) {
            // Find column
            $column = $columns->where('name', $name)->first();

            if (isset($column) && $column->options->get('sortable', true)) {
                $direction = request()->get('dir', 'asc');
                $column->order = $direction;

                $query->orderBy($name, $direction);
            }
        };
    }

}