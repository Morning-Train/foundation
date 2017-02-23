<?php

namespace morningtrain\Themer\Contracts;

use morningtrain\Themer\Extensions\HasAssets;
use morningtrain\Themer\Extensions\HasBodyClass;
use morningtrain\Themer\Extensions\HasConfig;

class Theme
{

    use HasAssets, HasBodyClass;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    function __construct($name)
    {
        $this->name = $name;
        $this->slug = strtolower($name);
        $this->actions = [];

        $this->register();
    }

    /*
     * Actions
     */

    protected $actions;

    public function addAction(string $actionName, callable $callback)
    {
        if (!isset($this->actions[$actionName])) {
            $this->actions[$actionName] = [];
        }

        $this->actions[$actionName][] = $callback;

        return $this;
    }

    public function do($actionName)
    {
        // Fetch arguments
        $args = array_slice(func_get_args(), 1);

        if (isset($this->actions[$actionName]) && is_array($this->actions[$actionName])) {
            foreach ($this->actions[$actionName] as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }

    /*
     * Registration helper
     */

    protected function register()
    {

        // Add view namespace
        view()->addNamespace($this->name, base_path('resources/themes/' . $this->name . '/views'));

        $this->registerConfig();
        $this->registerAssets();
    }

    /*
     * Blade views
     */

    public function view($viewName)
    {
        return strlen($this->name) > 0 ? $this->name . '::' . $viewName : $viewName;
    }

    public function render($viewName)
    {
        return view($this->view($viewName));
    }

}