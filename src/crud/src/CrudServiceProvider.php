<?php

namespace morningtrain\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use morningtrain\Crud\Commands\NewCrud;
use morningtrain\Crud\Components\Filter;
use morningtrain\Crud\Components\ViewHelper;
use morningtrain\Crud\Services\Crud;
use Illuminate\Support\Collection;
use morningtrain\Janitor\Exceptions\JanitorException;

class CrudServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Publish files
        $this->publish();

        // Register commands
        $this->commands([
            NewCrud::class,
        ]);

        // Register service
        $this->app->singleton(Crud::class, function ($app) {
            return new Crud($app->make(Router::class));
        });

        // Custom filters
        $this->registerCustomFilters();
    }

    /**
     * Files to publish
     */
    public function publish()
    {

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/crud.php' => config_path('crud.php'),

        ], 'config');

        // Publish gulp file
        $this->publishes([
            __DIR__ . '/../gulp/fields.js' => base_path('gulp/fields.js'),

        ], 'gulp');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views/crud' => base_path('resources/views/crud'),

        ], 'views');

        // Publish fields
        $this->publishes([
            __DIR__ . '/../resources/fields' => base_path('resources/fields'),

        ], 'fields');

        // Publish stubs
        $this->publishes([
            __DIR__ . '/../resources/stubs' => base_path('resources/stubs'),

        ], 'stubs');

        // Publish lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang'),

        ], 'lang');

    }

    public function registerCustomFilters()
    {

        Filter::registerCustomFilter('order', function (array $args) {
            // Fields
            $args['fields'] = [
                'order',
                'direction' => ['required' => false, 'default' => 'asc']
            ];

            // Get columns
            if (!isset($args['columns']) || !($args['columns'] instanceof Collection)) {
                throw new JanitorException('The order filter requires a `columns` argument to be passed.');
            }

            // Remove render
            unset($args['render']);

            $args['apply'] = function (Filter $filter, $query) {
                // Find column
                $name = $filter->value('order');
                $columns = $filter->columns;
                $column = $columns->where('name', $name)->first();

                if (isset($column) && $column->options->get('sortable', true)) {
                    // Remove order from already ordered columns
                    $columns->each(function ($column) {
                        if ($column->order !== 'none') {
                            $column->order = 'none';
                        }
                    });

                    $direction = $filter->value('direction');
                    $column->order = $direction;

                    // Check if custom sorter
                    $sorter = $column->options->get('sort');

                    if (is_callable($sorter)) {
                        $sorter($query, $name, $direction);
                    } else {
                        $query->orderBy($name, $direction);
                    }
                }
            };

            return $args;
        });

    }

}