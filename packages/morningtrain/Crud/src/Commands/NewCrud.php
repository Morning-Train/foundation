<?php

namespace morningtrain\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use morningtrain\Crud\Contracts\Controller;
use morningtrain\Crud\Contracts\Model;
use Symfony\Component\Console\Input\InputArgument;

class NewCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:new {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new crud stack';

    protected function getArguments() {
        return [
            [
                'model',
                InputArgument::REQUIRED,
                'Singular model name'
            ]
        ];
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->stub = app()->make('stub');
    }

    /*
     * Helpers
     */

    protected $stub;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $modelName = ucfirst($this->argument('model'));
        $controllerName = str_plural($modelName).'Controller';
        $migrationName = strtolower(str_plural($modelName));

        $migration = $this->createMigration($migrationName);
        $model = $this->createModel($modelName);
        $controller = $this->createController($controllerName, $model);

        $this->info('Everything has been successfully created!');
    }

    protected function createMigration( $name ) {
        $filename = date('Y_m_d').'_000000_create_'.$name.'_table';
        $destination = config('crud.paths.migrations', database_path('migrations')) . '/' . $filename . '.php';
        $className = 'Create'.ucfirst($name).'Table';

        $this->stub->create('crud.migration', $destination, [
            'imports'   => [
                Migration::class,
                Blueprint::class,
                Schema::class
            ],
            'class'     => $className,
            'extends'   => Migration::class,
            'table'     => $name
        ]);

        return $className;
    }

    protected function createModel( $name ) {
        $namespace = config('crud.namespaces.models', 'App\\Models');
        $destination = config('crud.paths.models', app_path('Models')) . '/' . $name . '.php';

        $this->stub->create('crud.model', $destination, [
            'namespace' => $namespace,
            'imports'   => [
                Model::class
            ],
            'class'     => $name,
            'extends'   => Model::class
        ]);

        return "$namespace\\$name";
    }

    protected function createController( $name, $model ) {
        $namespace = config('crud.namespaces.controllers', 'App\\Http\\Controllers');
        $destination = config('crud.paths.controllers', app_path('Http\\Controllers')) . '/' . $name . '.php';

        $this->stub->create('crud.controller', $destination, [
            'namespace' => $namespace,
            'imports'   => [
                Controller::class
            ],
            'class'     => $name,
            'extends'   => Controller::class,
            'model'     => '\\' . $model . '::class'
        ]);

        return "$namespace\\$name";
    }
}
