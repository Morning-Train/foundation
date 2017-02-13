<?php

namespace morningtrain\Crud\Components;

use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use morningtrain\Crud\Contracts\Model;

class Field
{

    public static function create(array $options = [])
    {
        $class = static::class;

        return new $class($options);
    }

    /*
     * Custom fields constructors
     */

    protected static $customFields = [];

    public static function registerCustomField($type, \Closure $callback)
    {
        static::$customFields[$type] = $callback;
    }

    /*
     * Helper to create blade rendering fields
     */

    public static function __callStatic($name, $arguments)
    {
        // Convert name to blade friendly name
        $type = strtolower(preg_replace('/\B([A-Z])/', '-$1', $name));
        $callback = isset(static::$customFields[$type]) ? static::$customFields[$type] : null;
        $args = array_merge(
            [
                'render' => function (Field $field, Model $resource, ViewHelper $helper, array $params) use ($type) {
                    return view($helper->view("fields.$type"))->with(array_merge($params, [
                        'crud' => $helper,
                        'entry' => $resource,
                        'field' => $field,
                        'value' => $field->value($resource),

                    ]))->render();
                }
            ],
            isset($arguments[0]) && is_array($arguments[0]) ? $arguments[0] : []
        );

        if (is_callable($callback)) {
            $args = $callback($args);
        }

        return $args instanceof Field ? $args : static::create($args);
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

    /*
     * Label accessor
     */

    public function getLabel()
    {
        return $this->options->has('label') ?
            $this->options->get('label') :
            ViewHelper::current()->trans('fields.' . $this->options->get('name') . '.label');
    }

    /*
     * Placeholder accessor
     */

    public function getPlaceholder()
    {
        return $this->options->has('placeholder') ?
            $this->options->get('placeholder') :
            ViewHelper::current()->trans('fields.' . $this->options->get('name') . '.placeholder');
    }

    /*
     * Id generator
     */

    public function getId()
    {
        if (!$this->options->has('id')) {
            $this->options->set('id', md5($this->options->get('name', uniqid()) . time()));
        }

        return $this->options->get('id');
    }

    /*
     * Attributes getter
     */

    public function getAttributes()
    {
        // Compute push attributes
        $attributes = [];
        $pushAttrs = $this->options->get('$attributes', []);

        foreach ($pushAttrs as $key => $query) {
            if (!is_array($query)) {
                $query = ['key' => $query];
            }

            // Normalize query
            $query = array_merge(['key' => null, 'default' => null], $query);

            // Validate query
            if (!is_string($query['key']) || (strlen($query['key']) === 0)) {
                continue;
            }

            // Compute query
            $queryKey = $query['key'];
            $value = $this->$queryKey;

            if (is_null($value)) {
                $value = $query['default'];
            }

            if (!is_null($value)) {
                // Normalize key
                if (is_int($key)) {
                    $key = $query['key'];
                }

                $attributes[$key] = $value;
            }
        }

        return array_merge($this->options->get('attributes', []), $attributes, [
            'id' => $this->id,
        ]);
    }

    /*
     * Rules
     */

    public function rules(Model $resource, Request $request)
    {
        $name = $this->options->get('name');
        $rules = $this->options->get('rules');

        // String rules
        if (is_string($rules) && (strlen($rules) > 0)) {
            return is_string($name) && (strlen($name) > 0) ? [$name => $rules] : [];
        }

        // Array rules
        if (is_array($rules)) {
            return $rules;
        }

        // Callable
        if ($rules instanceof \Closure) {
            $dynamicRules = $rules($this, $resource, $request);

            if (is_array($dynamicRules)) {
                return $dynamicRules;
            }

            if (is_string($dynamicRules)) {
                return is_string($name) && (strlen($name) > 0) ? [$name => $dynamicRules] : [];
            }
        }

        return [];
    }

    /*
     * Extensions
     */

    public function render(Model $resource, ViewHelper $helper)
    {
        $renderer = $this->render;

        if (is_callable($renderer)) {
            return $renderer($this, $resource, $helper, $this->options->get('params', []));
        }

        return '';
    }

    public function update(Model $resource, Request $request)
    {
        $setter = $this->options->get('update');

        if ($setter === false) {
            return;
        }

        if (is_callable($setter)) {
            return $setter($this, $resource, $request);
        }

        // Set value by name
        $name = $this->options->get('name');

        if (is_string($name) && (strlen($name) > 0)) {
            $value = $request->get($name);

            $resource->$name = $value;
        }
    }

    public function value(Model $resource)
    {
        $name = $this->options->get('name');
        $getter = $this->options->get('value');

        // No value
        if ($getter === false) {
            return;
        }

        if (is_callable($getter)) {
            return $getter($this, $resource);
        }

        return $resource->$name;
    }

}