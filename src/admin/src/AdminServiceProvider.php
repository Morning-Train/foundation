<?php

namespace morningtrain\Admin;

use Illuminate\Support\ServiceProvider;
use morningtrain\Admin\Commands\Update;
use morningtrain\Admin\Features\AdminFeature;
use morningtrain\Admin\Features\AuthFeature;
use morningtrain\Crud\Components\Column;
use morningtrain\Crud\Components\Field;
use morningtrain\Crud\Components\ViewHelper;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Janitor\Services\Janitor;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Janitor $janitor)
    {
        // Register features
        $janitor->provide([
            AuthFeature::class,
            AdminFeature::class,
        ]);
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
            Update::class,
        ]);

        // Register custom columns
        $this->registerCustomColumns();

        // Register custom fields
        $this->registerCustomFields();
    }

    /**
     * Files to publish
     */
    public function publish()
    {

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),

        ], 'config');

        // Publish lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang'),

        ], 'language');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views'),

        ], 'views');

        // Publish themes
        $this->publishes([
            __DIR__ . '/../resources/themes' => base_path('resources/themes'),

        ], 'themes');

    }

    public function registerCustomFields()
    {

        /*
         * --------------------------------------
         * Input fields
         * --------------------------------------
         */

        Field::registerCustomField('input', function (array $args) {
            // Register push attributes
            $args['$attributes'] = array_merge(
                isset($args['$attributes']) && is_array($args['$attributes']) ? $args['$attributes'] : [],
                [
                    'class',
                    'placeholder'
                ]
            );

            return $args;
        });

        Field::registerCustomField('text', function (array $args) {
            // Set type
            $args['type'] = 'text';

            // Remove current render
            unset($args['render']);

            return Field::input($args);
        });

        Field::registerCustomField('email', function (array $args) {
            // Set type
            $args['type'] = 'email';

            // Remove current render
            unset($args['render']);

            return Field::input($args);
        });

        Field::registerCustomField('password', function (array $args) {
            // Set type
            $args['type'] = 'password';

            // Remove current render
            unset($args['render']);

            return Field::input($args);
        });

        /*
         * --------------------------------------
         * Select fields
         * --------------------------------------
         */

        Field::registerCustomField('select', function (array $args) {
            $render = $args['render'];

            $args['render'] = function (Field $field, Model $resource, ViewHelper $helper, array $params) use ($render
            ) {
                $optionsConstructor = $field->options->get('options', []);
                $options = [];

                if (is_callable($optionsConstructor)) {
                    $options = $optionsConstructor($resource);
                } else {
                    if (is_array($optionsConstructor)) {
                        $options = $optionsConstructor;
                    }
                }

                return $render($field, $resource, $helper, array_merge($params, [
                    'options' => $options
                ]));
            };

            return $args;
        });

    }

    public function registerCustomColumns()
    {
        // Actions
        Column::registerCustomColumn('actions', function (array $args) {
            return array_merge([
                'name' => 'actions',
                'sortable' => false,
                'class' => 'align-right'

            ], $args);
        });
    }
}