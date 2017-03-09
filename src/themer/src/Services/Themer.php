<?php

namespace morningtrain\Themer\Services;

use morningtrain\Janitor\Exceptions\JanitorException;
use morningtrain\Janitor\Services\Janitor;
use morningtrain\Themer\Contracts\Theme;

class Themer
{

    /**
     * @var Janitor
     */
    protected $janitor;

    public function __construct()
    {
        $this->janitor = app()->make(Janitor::class);
    }

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
        $this->janitor->trigger('theme.load', $this->current);

        return $this->current;
    }

    /*
     * Load listener
     */

    public function onLoad($name, $callback = null)
    {
        // 1 argument only
        if (is_null($callback)) {
            $callback = $name;
            $name = null;
        }

        // Validate callback
        if (!($callback instanceof \Closure)) {
            throw new JanitorException('Invalid callback passed to Theme::onLoad!');
        }

        $this->janitor->on('theme.load', function (Theme $theme) use ($name, $callback) {
            if (is_null($name) || ($theme->getName() === $name)) {
                return $callback($theme);
            }
        });

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