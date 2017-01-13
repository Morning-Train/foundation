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

    public function trans( $query, $namespace = null ) {
        $namespace = isset($namespace) ? $namespace : $this->options->get('namespace', '');
        return strlen($namespace) > 0 ? trans("crud.$namespace.$query") : trans($query);
    }

    public function title( $route = null ) {
        $slug = isset($route) ? $route : $this->options->get('slug', '');
        return strlen($slug) > 0 ? $this->trans("title.$slug") : '';
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