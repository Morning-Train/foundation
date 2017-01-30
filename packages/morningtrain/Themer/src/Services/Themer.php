<?php

namespace morningtrain\Themer\Services;

use morningtrain\Themer\Contracts\Theme;

class Themer
{

    /**
     * @var Theme
     */
    protected $current;

    /**
     * Returns current theme
     *
     * @return Theme
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Loads a specific theme
     *
     * @param $name
     * @return Theme
     */
    public function load($name)
    {
        $namespace = config('themer.namespace', 'App\Themes');
        $name = ucfirst($name);
        $className = $namespace . '\\' . $name . 'Theme';

        // Create theme
        $this->current = class_exists($className) ? new $className($name) : new Theme($name);

        // Trigger onLoad
        foreach ($this->onload as $callback) {
            $callback($this->current);
        }

        return $this->current;
    }

    /*
     * Load listener
     */

    protected $onload = [];

    public function onLoad(callable $callback)
    {
        $this->onload[] = $callback;

        return $this;
    }

    /*
     * Current theme call
     */

    function __call($name, $arguments)
    {
        if (!isset($this->current)) {
            throw new \Exception('No theme has been loaded!');
        }

        return call_user_func_array([$this->current, $name], $arguments);
    }
}