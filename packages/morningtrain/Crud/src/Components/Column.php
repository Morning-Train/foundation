<?php

namespace morningtrain\Crud\Components;

use Illuminate\Config\Repository;
use morningtrain\Crud\Contracts\Model;

class Column {

    public static function create( array $options = [] ) {
        $class = static::class;
        return new $class($options);
    }

    /*
     * Helper to create blade rendering columns
     */

    public static function __callStatic( $name, $arguments ) {
        // Convert name to blade friendly name
        $name = strtolower(preg_replace('/\B([A-Z])/', '-$1', $name));

        return static::create(array_merge(
            isset($arguments[0]) && is_array($arguments[0]) ? $arguments[0] : [],
            [
                'render'    => function( Column $column, Model $resource, ViewHelper $helper, array $params ) use( $name ) {
                    return view($helper->view("columns.$name"))->with(array_merge($params, [
                        'crud'      => $helper,
                        'entry'     => $resource,
                        'column'    => $column

                    ]))->render();
                }
            ]
        ));
    }

    /**
     * @var Repository
     */

    public $options;

    function __construct( array $options = [] ) {
        $this->options = new Repository($options);
    }

    function __isset( $name ) {
        $value = $this->$name;
        return isset($value);
    }

    function __get( $name ) {

        // Try to look for method getter
        $methodLink = [ $this, 'get' . ucfirst($name) ];

        if (is_callable($methodLink)) {
            return $methodLink();
        }

        // Get value from options
        if ($this->options->has($name)) {
            return $this->options->get($name);
        }
    }

    function __set( $name, $value ) {

        // Try to look for method getter
        $methodLink = [ $this, 'set' . ucfirst($name) ];

        if (is_callable($methodLink)) {
            return $methodLink($value);
        }

        // Get value from options
        $this->options->set($name, $value);
    }

    public function render( Model $resource, ViewHelper $helper ) {
        // Get renderer from options
        $renderer = $this->render;

        if (is_callable($renderer)) {
            return $renderer($this, $resource, $helper, $this->options->get('params', []));
        }

        // Get by name
        $name = $this->name;

        if (is_string($name) && (strlen($name) > 0)) {
            return $resource->$name;
        }

        return '';
    }

}