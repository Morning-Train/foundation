<?php

namespace morningtrain\Crud\Components;

use morningtrain\Janitor\Exceptions\JanitorException;

class Filter
{

    public static function create(array $options = [])
    {
        $class = static::class;
        return new $class($options);
    }

    /*
     * Custom fields constructors
     */

    protected static $customFilters = [];

    public static function registerCustomFilter($type, \Closure $callback)
    {
        static::$customFilters[$type] = $callback;
    }

    /*
     * Helper to create blade rendering filters
     */

    public static function __callStatic($name, $arguments)
    {
        // Convert name to blade friendly name
        $type = strtolower(preg_replace('/\B([A-Z])/', '-$1', $name));
        $callback = isset(static::$customFilters[$type]) ? static::$customFilters[$type] : null;
        $args = array_merge(
            isset($arguments[0]) && is_array($arguments[0]) ? $arguments[0] : [],
            [
                'render' => function (Filter $filter, ViewHelper $helper, array $params) use ($type) {
                    return view($helper->view("filters.$type"))->with(array_merge($params, [
                        'crud' => $helper,
                        'filter' => $filter,
                        'value' => $filter->value(),

                    ]))->render();
                }
            ]
        );

        if (is_callable($callback)) {
            $args = $callback($args);
        }

        return static::create($args);
    }

    /**
     * @var Repository
     */

    public $options;

    function __construct(array $options = [])
    {
        $this->options = new Repository($options);

        // Validate
        if (!isset($this->name) || !is_string($this->name) || (strlen($this->name) === 0)) {
            throw new JanitorException('A filter must have a name to register to.');
        }

        if (!isset($this->apply) || !($this->apply instanceof \Closure)) {
            throw new JanitorException('A filter must have contain an `apply` closure.');
        }
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

    /*
     * Methods
     */

    public function render(ViewHelper $helper)
    {
        $render = $this->render;

        if (is_callable($render)) {
            return $render($this, $helper, $this->options->get('params', []));
        }
    }

    public function value()
    {
        return request()->get($this->name);
    }

}