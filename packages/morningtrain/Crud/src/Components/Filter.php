<?php

namespace morningtrain\Crud\Components;

use Illuminate\Support\Collection;

abstract class Filter {

    public static function order( Collection $columns ) {
        return function($query, $name) use($columns) {
            // Find column
            $column = $columns->where('name', $name)->first();

            if (isset($column) && $column->options->get('sortable', true)) {
                // Remove order from already ordered columns
                $columns->each(function( $column ) {
                    if ($column->order !== 'none') {
                        $column->order = 'none';
                    }
                });

                $direction = request()->get('direction', 'asc');
                $column->order = $direction;

                // Check if custom sorter
                $sorter = $column->options->get('sort');

                if (is_callable($sorter)) {
                    $sorter($query, $name, $direction);
                }
                else {
                    $query->orderBy($name, $direction);
                }
            }
        };
    }

}