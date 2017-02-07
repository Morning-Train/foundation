<?php

namespace morningtrain\Crud\Components;

use Illuminate\Config\Repository;
use morningtrain\Crud\Contracts\Model;

class Column
{

    public static function create(array $options = [])
    {
        $class = static::class;

        return new $class($options);
    }

    /*
     * Custom columns
     */

    protected static $customColumns = [];

    public static function registerCustomColumn($type, \Closure $callback)
    {
        static::$customColumns[$type] = $callback;
    }

    /*
     * Helper to create blade rendering columns
     */

    public static function __callStatic($name, $arguments)
    {
        // Convert name to blade friendly name
        $type = strtolower(preg_replace('/\B([A-Z])/', '-$1', $name));
        $callback = isset(static::$customColumns[$type]) ? static::$customColumns[$type] : null;
        $args = array_merge(
            [
                'render' => function (Column $column, Model $resource, ViewHelper $helper, array $params) use ($type) {
                    return view($helper->view("columns.$type"))->with(array_merge($params, [
                        'crud' => $helper,
                        'entry' => $resource,
                        'column' => $column

                    ]))->render();
                }
            ],
            isset($arguments[0]) && is_array($arguments[0]) ? $arguments[0] : []
        );

        if (is_callable($callback)) {
            $args = $callback($args);
        }

        return $args instanceof Column ? $args : static::create($args);
    }

    /**
     * @var Repository
     */

    public $options;

    function __construct(array $options = [])
    {
        $this->options = new Repository($options);
    }

    function __isset($name)
    {
        $value = $this->$name;

        return isset($value);
    }

    function __get($name)
    {

        // Try to look for method getter
        $methodLink = [$this, 'get' . ucfirst($name)];

        if (is_callable($methodLink)) {
            return $methodLink();
        }

        // Get value from options
        if ($this->options->has($name)) {
            return $this->options->get($name);
        }
    }

    function __set($name, $value)
    {

        // Try to look for method getter
        $methodLink = [$this, 'set' . ucfirst($name)];

        if (is_callable($methodLink)) {
            return $methodLink($value);
        }

        // Get value from options
        $this->options->set($name, $value);
    }

    public function render(Model $resource, ViewHelper $helper)
    {
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