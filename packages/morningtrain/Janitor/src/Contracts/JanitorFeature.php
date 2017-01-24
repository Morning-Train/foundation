<?php

namespace morningtrain\Janitor\Contracts;

use Illuminate\Routing\Router;
use morningtrain\Janitor\Services\Janitor;

abstract class JanitorFeature {

    /**
     * @var array
     */
    protected $models = [];

    /**
     * @var array
     */
    protected $controllers = [];

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * @var array
     */
    protected $migrations = [];

    /**
     * @var string
     */
    protected $routerGroup = '';

    /**
     * @var array
     */
    protected $routerOptions = [];

    /**
     * @param Router $router
     */
    protected function routes( Router $router ) {
        // Register your routes here
    }

    /**
     * Returns a closure called into janitor:publish when option --init is passed (before any publishing is made)
     *
     * @return Closure|null
     */
    protected function initializer() {
        // Return a closure here
    }

    /**
     * Returns a closure called in janitor:publish after all default publishing is done
     */
    protected function publisher() {
        // Return a closure here
    }

    /*
     * Accessors (or dynamic getters)
     */

    protected function models() {
        return $this->models;
    }

    protected function controllers() {
        return $this->controllers;
    }

    protected function classes() {
        return $this->classes;
    }

    protected function migrations() {
        return $this->migrations;
    }

    protected function middleware() {
        return $this->middleware;
    }

    protected function routerGroup() {
        return $this->routerGroup;
    }

    protected function routerOptions() {
        return $this->routerOptions;
    }

    /**
     * @param Janitor $janitor
     */
    public function register( Janitor $janitor ) {
        $feature = $this;

        // Initializer
        $initializer = $this->initializer();

        if ($initializer instanceof \Closure) {
            $janitor->registerInitializer($initializer);
        }

        // Publisher
        $publisher = $this->publisher();

        if ($publisher instanceof \Closure) {
            $janitor->registerPublisher($publisher);
        }

        // Models
        $janitor->registerModels($this->models());

        // Controllers
        $janitor->registerControllers($this->controllers());

        // Classes
        $janitor->registerClasses($this->classes());

        // Migration
        $janitor->registerMigrations($this->migrations());

        // Middleware
        $janitor->registerMiddleware($this->middleware());

        // Route group
        $group = $this->routerGroup();

        if (is_string($group) && (strlen($group) > 0)) {
            $janitor->registerRoutes($group, $this->routerOptions(), function( Router $router ) use( $feature ) {
                return $feature->routes($router);
            });
        }
    }

}