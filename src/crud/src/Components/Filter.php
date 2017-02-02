<?php

namespace morningtrain\Crud\Components;

use morningtrain\Janitor\Exceptions\JanitorException;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;

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
                        'filter' => $filter

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
        $this->buildFields();
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
     * Accessors
     */

    protected function getRenderable()
    {
        return is_callable($this->options->get('render'));
    }

    /*
     * Helpers
     */

    protected function buildFields()
    {
        $rawFields = $this->options->get('fields', []);

        // Field ?
        if ((count($rawFields) === 0) && $this->options->has('field')) {
            $rawFields = [$this->options->get('field')];
        }

        $fields = [];

        foreach ($rawFields as $field => $params) {
            if (is_int($field)) {
                $field = $params;
                $params = null;
            }

            if (!is_array($params)) {
                $params = [];
            }

            $params = array_merge([
                'required' => true,
                'default' => null

            ], $params);

            $fields[$field] = $params;
        }

        $this->options->set('fields', $fields);
    }

    /*
     * Methods
     */

    public function value($field)
    {
        if ($this->options->has("fields.$field")) {
            return request()->get($field, $this->options->get("fields.$field.default"));
        }
    }

    public function render(ViewHelper $helper)
    {
        $render = $this->render;

        if (is_callable($render)) {
            return $render($this, $helper, $this->options->get('params', []));
        }
    }

    public function apply($query, Request $request)
    {
        $apply = $this->apply;

        if (is_callable($apply)) {

            // Validate fields
            $fields = $this->options->get('fields');

            foreach ($fields as $field => $params) {
                if ($params['required'] && !$request->has($field)) {
                    return;
                }
            }

            $apply($this, $query);
        }
    }

}