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
    protected $middleware = [];

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
     * @param Janitor $janitor
     */
    public function register( Janitor $janitor ) {
        // Models
        $janitor->registerModels($this->models);

        // Controllers
        $janitor->registerControllers($this->controllers);

        // Middleware
        $janitor->registerMiddleware($this->middleware);

        // Route group
        if (is_string($this->routerGroup) && (strlen($this->routerGroup) > 0)) {
            $janitor->registerRoutes($this->routerGroup, $this->routerOptions, [ $this, 'routes' ]);
        }
    }

}