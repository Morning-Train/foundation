<?php

namespace morningtrain\Janitor\Services;

use Illuminate\Routing\Router;
use morningtrain\Janitor\Exceptions\JanitorException;

class Janitor {

    /*
     * Class registration (aliases)
     */

    public function registerClasses( array $originals, string $namespace = 'App' ) {
        foreach($originals as $base => $original) {
            $baseName = is_string($base) ? "$namespace\\$base" : $base;
            $classNameParts = explode("\\", $original);
            $className = end($classNameParts);
            $alias = "$baseName\\$className";

            if (class_exists($alias)) {
                throw new JanitorException("Class alias `$alias` already exists.");
            }

            class_alias($original, $alias);
        }
    }

    public function registerModels( array $models ) {
        return $this->registerClasses($models, config('janitor.namespaces.models', 'App\Models'));
    }

    public function registerControllers( array $controllers ) {
        return $this->registerClasses($controllers, config('janitor.namespaces.controllers', 'App\Http\Controllers'));
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
        $router = app()->make(Router::class);

        $router->group($options, $callback);
    }

    public function registerMiddleware( array $middleware ) {
        $router = app()->make(Router::class);

        foreach($middleware as $key => $handler) {
            // Group
            if (is_array($handler)) {
                $router->middlewareGroup($key, $handler);
            }
            else {
                $router->middleware($key, $handler);
            }
        }
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