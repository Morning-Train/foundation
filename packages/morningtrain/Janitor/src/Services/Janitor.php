<?php

namespace morningtrain\Janitor\Services;

use Illuminate\Routing\Router;
use morningtrain\Janitor\Exceptions\JanitorException;
use morningtrain\Janitor\Middleware\GlobalMiddleware;

class Janitor {

    function __construct( Router $router ) {
        $this->router = $router;
    }

    /*
     * Vendors
     */

    /**
     * @var Router
     */
    protected $router;

    /*
     * Model registration
     */

    /**
     * @var array
     */
    protected $models = [];

    public function registerModels( array $models, string $namespace = '' ) {
        foreach($models as $name => $class) {
            if (is_int($name)) {
                $classParts = explode('\\', $class);
                $name = end($classParts);
            }

            // Add namespace to name
            if (strlen($namespace) > 0) {
                $name = $namespace . '\\' . $name;
            }

            $this->models[$name] = $class;
        }
    }

    public function getRegisteredModels() {
        return $this->models;
    }

    /*
     * Controller registration
     */

    protected $controllers = [];

    public function registerControllers( array $controllers, string $namespace = '' ) {
        foreach($controllers as $name => $class) {
            if (is_int($name)) {
                $classParts = explode('\\', $class);
                $name = end($classParts);
            }

            // Add namespace to name
            if (strlen($namespace) > 0) {
                $name = $namespace . '\\' . $name;
            }

            $this->controllers[$name] = $class;
        }
    }

    public function getRegisteredControllers() {
        return $this->controllers;
    }

    /*
     * Middleware registration
     */

    public function registerMiddleware( array $middleware ) {
        foreach($middleware as $key => $handler) {
            // Global
            if (is_int($key)) {
                GlobalMiddleware::append($handler);
            }

            // Group
            else if (is_array($handler)) {
                $this->router->middlewareGroup($key, $handler);
            }

            // Named middleware
            else {
                $this->router->middleware($key, $handler);
            }
        }
    }

    /*
     * Route registration
     */

    public function registerRoutes( string $groupName, array $options, callable $callback ) {
        $options = array_merge($options, config("janitor.routing.groups.$groupName", []));
        $controllerNamespace = config('janitor.namespaces.controllers');

        $groupSlug = strtolower($groupName);

        // guess prefix
        if (!isset($options['prefix'])) {
            $options['prefix'] = $groupSlug;
        }

        // guess middleware
        if (!isset($options['middleware'])) {
            $options['middleware'] = $groupSlug;
        }

        // assign default namespace
        if (!isset($options['namespace'])) {
            $options['namespace'] = $controllerNamespace. '\\' . ucfirst($groupSlug);
        }

        // assign default theme
        if (!isset($options['theme'])) {
            $options['theme'] = ucfirst($groupSlug);
        }

        // Register route group
        $this->router->group($options, $callback);
    }

    /*
     * Feature registration (packages)
     */

    public function provide( array $features ) {
        foreach( $features as $featureClass ) {
            if (!class_exists($featureClass)) {
                throw new JanitorException("Feature `$featureClass` does not exist.");
            }

            $feature = new $featureClass();

            if (!method_exists($feature, 'register')) {
                throw new JanitorException("Feature `$featureClass` does not provide a registration method.");
            }

            $feature->register($this);
        }
    }

}