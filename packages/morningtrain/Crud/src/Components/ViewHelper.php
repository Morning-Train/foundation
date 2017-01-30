<?php

namespace morningtrain\Crud\Components;

use Illuminate\Config\Repository;

class ViewHelper {

    function __construct( array $options = [] ) {
        $this->options = new Repository($options);
    }

    /**
     * @var Repository
     */
    public $options;

    function __get( $name ) {

        // Get value from options
        if ($this->options->has($name)) {
            return $this->options->get($name);
        }

    }

    /*
     * Views
     */

    public function view( $name ) {
        $namespace = $this->options->get('viewNamespace', '');
        return strlen($namespace) > 0 ? "$namespace.crud.$name" : "crud.$name";
    }

    /*
     * Translations
     */

    public function trans( $query, array $args = null, $namespace = null, $default = null ) {
        if (!isset($namespace)) {
            $namespace = [
                $this->options->get('namespace'),
                'common'
            ];
        }

        if (!is_array($namespace)) {
            $namespace = [ $namespace ];
        }

        if (!is_array($args)) {
            $args = [ 'type' => $this->options->get('singularName') ];
        }

        if (is_null($default)) {
            $default = $query;
        }

        do {
            $ns = array_shift($namespace);
            $key = "crud.$ns.$query";
            $trans = trans($key, $args);
        }
        while(($key === $trans) && (count($namespace) > 0));

        return $key === $trans ? $default : $trans;
    }

    public function title( $route = null ) {
        $slug = isset($route) ? $route : $this->options->get('slug', '');

        return $this->trans("title.$slug", $slug === 'index' ? [
            'type'  => ucfirst($this->options->get('pluralName'))

        ] : null);
    }

    /*
     * Routing
     */

    public function routeName( $slug = null ) {
        $baseRoute = $this->options->get('baseRoute', '');
        $slug = isset($slug) ? $slug : $this->options->get('slug', '');

        return strlen($baseRoute) > 0 ? "$baseRoute.$slug" : $slug;
    }

    public function route( $slug = null, array $args = [] ) {
        return route($this->routeName($slug), $args);
    }

}