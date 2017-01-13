<?php

namespace morningtrain\Crud\Components;

use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use morningtrain\Crud\Contracts\Model;

class Field {

    public static function create( array $options = [] ) {
        $class = static::class;
        return new $class($options);
    }

    /**
     * @var Repository
     */

    public $options;

    function __construct( array $options = [] ) {
        $this->options = new Repository($options);
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

    /*
     * Extensions
     */

    public function render( Model $resource ) {
        $renderer = $this->render;

        if (is_callable($renderer)) {
            $renderer($resource);
        }
    }

    public function getValue( Model $resource ) {
        $getter = $this->options->get('value.get');

        if (is_callable($getter)) {
            return $getter($resource);
        }
    }

    public function setValue( Model $resource, Request $request ) {
        $setter = $this->options->get('value.set');

        if (is_callable($setter)) {
            return $setter($resource, $request);
        }
    }

}