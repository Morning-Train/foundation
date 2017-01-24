<?php

namespace morningtrain\Admin\Features;

use morningtrain\Admin\Themes\AdminTheme;
use morningtrain\Crud\Services\Crud;
use morningtrain\Janitor\Contracts\JanitorFeature;
use Illuminate\Routing\Router;

class AdminFeature extends JanitorFeature {

    /**
     * @var Crud
     */
    protected $crud;

    function __construct() {
        $this->crud = app()->make(Crud::class);
    }

    protected $classes = [
        'Themes\\AdminTheme'    => AdminTheme::class
    ];

    protected $routerGroup = 'admin';

    protected function routerOptions() {
        return [
            'prefix'        => trans('admin.prefix'),
            'middleware'    => 'web',
            'theme'         => 'Admin',
            'namespace'     => 'App\Http\Controllers'
        ];
    }

    protected function routes( Router $router ) {
        $items = config('admin.items', []);

        // Define base admin route
        $router->get('', [
            'as'    => 'admin',
            'uses'  => function() use( $items ) {
                if (count($items) === 0) {
                    return redirect('/');
                }

                // Find first model
                $firstModel = null;

                foreach( $items as $model => $params ) {
                    $firstModel = is_int($model) ? $params : $model;
                    break;
                }

                // Route to first model
                $slug = (new $firstModel)->getPluralName();

                return redirect(route("admin.$slug.index"));
            }
        ]);

        // Define CRUD routes
        foreach($items as $model => $params) {
            if (is_int($model)) {
                $model = $params;
                $params = [];
            }

            // Prepare data
            $slug = (new $model)->getPluralName();

            $this->crud->route($model, [
                'base'          => "admin.$slug",
                'prefix'        => trans("crud.$slug.prefix"),
                'namespace'     => config('admin.crud.namespaces.controllers', 'App\\Http\\Controllers\\Admin')
            ]);
        }
    }

}