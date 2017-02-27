<?php

namespace morningtrain\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use morningtrain\Crud\Contracts\Controller;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Janitor\Helper\MigrationHelper;
use morningtrain\Stub\Services\Stub;
use Symfony\Component\Console\Input\InputArgument;

class NewCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:new {model} {--o} {--c} {--config=} {--stubs=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new crud stack';

    protected function getArguments()
    {
        return [
            [
                'model',
                InputArgument::REQUIRED,
                'Singular model name',
            ],
        ];
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Stub $stub)
    {
        parent::__construct();

        $this->stub = $stub;
        $this->migrator = new MigrationHelper();
    }

    /*
     * Config
     */

    protected $config_path = 'crud';
    protected $stubs_path = 'crud';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelName = ucfirst($this->argument('model'));
        $controllerName = str_plural($modelName) . 'Controller';
        $migrationName = strtolower(str_plural($modelName));

        // Determine options
        $config = $this->option('config');
        $stubs = $this->option('stubs');

        if (is_string($config) && (strlen($config) > 0)) {
            $this->config_path = $config;
        }

        if (is_string($stubs) && (strlen($stubs) > 0)) {
            $this->stubs_path = $stubs;
        }

        // Run
        $migration = $this->createMigration($migrationName);
        $model = $this->createModel($modelName);

        if (!$this->option('c')) {
            $controller = $this->createController($controllerName, $model);
        }

        $this->info('Everything has been successfully created!');
    }

    /*
     * Libraries
     */

    /**
     * @var Stub
     */
    protected $stub;

    /**
     * @var MigrationHelper
     */
    protected $migrator;

    /*
     * Helpers
     */

    protected function createMigration($name)
    {
        $stubs = $this->stubs_path;

        $migrationName = 'create_' . $name . '_table';
        $filename = $this->migrator->filename($migrationName);
        $destination = $this->migrator->path($filename);
        $className = 'Create' . ucfirst($name) . 'Table';
        $existingMigration = $this->migrator->exists($migrationName);

        if (($existingMigration === false) || $this->option('o')) {

            // Delete existing migration
            if ($existingMigration !== false) {
                unlink($this->migrator->path($existingMigration));
            }

            $this->stub->create("$stubs.migration", $destination, [
                'imports' => [
                    Migration::class,
                    Blueprint::class,
                    Schema::class,
                ],
                'class' => $className,
                'extends' => Migration::class,
                'table' => $name,
            ]);

        }

        return $className;
    }

    protected function createModel($name)
    {
        $config = $this->config_path;
        $stubs = $this->stubs_path;

        $namespace = config("$config.namespaces.models", config('crud.namespaces.models', 'App\\Models'));
        $destination = config("$config.paths.models",
                config('crud.paths.models', app_path('Models'))) . '/' . $name . '.php';
        $baseModel = config("$config.base-classes.models", config('crud.base-classes.models', Model::class));

        if (!file_exists($destination) || $this->option('o')) {

            $this->stub->create("$stubs.model", $destination, [
                'namespace' => $namespace,
                'imports' => [
                    $baseModel,
                ],
                'class' => $name,
                'extends' => $baseModel,
            ]);

        }

        return "$namespace\\$name";
    }

    protected function createController($name, $model)
    {
        $config = $this->config_path;
        $stubs = $this->stubs_path;

        $namespace = config("$config.namespaces.controllers",
            config('crud.namespaces.controllers', 'App\\Http\\Controllers'));
        $destination = config("$config.paths.controllers",
                config('crud.paths.controllers', app_path('Http\\Controllers'))) . '/' . $name . '.php';
        $baseController = config("$config.base-classes.controllers",
            config('crud.base-classes.controllers', Controller::class));

        if (!file_exists($destination) || $this->option('o')) {

            $this->stub->create("$stubs.controller", $destination, [
                'namespace' => $namespace,
                'imports' => [
                    $baseController,
                ],
                'class' => $name,
                'extends' => $baseController,
                'model' => '\\' . $model . '::class',
            ]);

        }

        return "$namespace\\$name";
    }
}
