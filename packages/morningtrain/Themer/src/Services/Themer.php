<?php

namespace morningtrain\Themer\Services;

use morningtrain\Themer\Contracts\Theme;

class Themer {

    /**
     * @var Theme
     */
    protected $current;

    public function current() {
        return $this->current;
    }

    public function load( $name ) {
        $namespace = config('themer.namespace', 'App\Themes');
        $name = ucfirst($name);
        $className = $namespace . '\\' . $name . 'Theme';

        // Create theme
        $this->current = class_exists($className) ? new $className($name) : new Theme($name);

        return $this->current;
    }

}