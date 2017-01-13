<?php

namespace morningtrain\Crud\Services;

use Illuminate\Routing\Router;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Crud\Exceptions\CrudException;

class Crud {

    /**
     * @var Router
     */
    protected $router;

    function __construct( Router $router ) {
        $this->router = $router;
    }

    public function route( $modelClass, array $options = [] ) {
        // Determine namespace
        if (!isset($options['namespace'])) {
            $options['namespace'] = config('crud.namespaces.controllers', 'App\\Http\\Controllers');
        }

        // Determine prefix
        if (!isset($options['prefix'])) {
            $options['prefix'] = $prefix = (new $modelClass)->getPluralName();
        }

        // Determine base route
        if (!isset($options['base'])) {
            $options['base'] = $options['prefix'];
        }

        // Determine controller
        if (!isset($options['controller'])) {
            $options['controller'] = ucfirst($options['prefix']).'Controller';
        }

        // Determine routes
        $options['routes'] = isset($options['routes']) && is_array($options['routes']) ?
            $options['routes'] :
            $this->getDefaultRoutes();

        // Filter options
        $options = $this->filterOptions($options);

        // Fetch controller and routes from options
        $base = isset($options['base']) ? $options['base'] : null;
        $controller = isset($options['controller']) ? $options['controller'] : null;
        $routes = $this->determineRoutes(isset($options['routes']) && is_array($options['routes']) ? $options['routes'] : null);

        unset($options['base'], $options['controller'], $options['routes']);

        if (!is_string($base) || (strlen($base) === 0)) {
            throw new CrudException('Invalid base route!');
        }

        if (!is_string($controller) || (strlen($controller) === 0)) {
            throw new CrudException('Invalid controller name!');
        }

        $this->router->group($options, function( $router ) use($base, $controller, $routes) {
            foreach ( $routes as $name => $params ) {
                $method = $params['method'];
                $action = $params['action'];
                $path = $params['path'];
                $where = isset($params['where']) ? $params['where'] : null;

                $route = $router->$method($path, [
                    'as'    => "$base.$name",
                    'uses'  => "$controller@$action"
                ]);

                if (is_array($where)) {
                    $route->where($where);
                }
            }
        });
    }

    /*
     * Filters
     */

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * Adds a new option filter
     *
     * @param callable $callback
     * @return $this
     */
    public function addFilter( callable $callback ) {
        $this->filters[] = $callback;
        return $this;
    }

    protected function filterOptions( $model, array $options ) {
        foreach($this->filters as $filter) {
            $options = $filter($model, $options);
        }

        return $options;
    }

    /*
     * Helpers
     */

    protected function getDefaultRoutes() {
        return [
            'index'     => [
                'method'    => 'get',
                'action'    => 'index',
                'path'      => trans('crud.common.routes.index')
            ],

            'create'    => [
                'method'    => 'get',
                'action'    => 'create',
                'path'      => trans('crud.common.routes.create')
            ],

            'edit'      => [
                'method'    => 'get',
                'action'    => 'show',
                'path'      => trans('crud.common.routes.edit') . '/{id}',
                'where'     => [
                    'id'    => '[0-9]+'
                ]
            ],

            'store'     => [
                'method'    => 'post',
                'action'    => 'store',
                'path'      => trans('crud.common.routes.store') . '/{id?}',
                'where'     => [
                    'id'    => '[0-9]+'
                ]
            ],

            'delete'    => [
                'method'    => 'get',
                'action'    => 'destroy',
                'path'      => trans('crud.common.routes.delete') . '/{id}',
                'where'     => [
                    'id'    => '[0-9]+'
                ]
            ]
        ];
    }

    protected function determineRoutes( array $onlyRoutes = null ) {
        $default = $this->getDefaultRoutes();

        if (is_null($onlyRoutes)) {
            return $default;
        }

        $routes = [];

        foreach($onlyRoutes as $routeName) {
            if (isset($default[$routeName])) {
                $routes[$routeName] = $default[$routeName];
            }
        }

        return $routes;
    }

}