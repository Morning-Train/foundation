<?php

namespace morningtrain\Janitor\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use morningtrain\Crud\Contracts\Controller;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Janitor\Services\Janitor;
use morningtrain\Stub\Services\Stub;
use Symfony\Component\Console\Input\InputArgument;

class Publish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'janitor:publish {--o}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes models and controllers for Janitor features';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->stub = app()->make('stub');
        $this->janitor = app()->make('janitor');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->publishModels($this->janitor->getRegisteredModels());
        $this->publishControllers($this->janitor->getRegisteredControllers());

        $this->info('Everything has been published!');
    }

    /*
     * Libraries
     */

    /**
     * @var Stub
     */
    protected $stub;

    /**
     * @var Janitor
     */
    protected $janitor;

    /*
     * Publish helpers
     */

    protected function publishModels( array $models ) {
        $baseNamespace = config('janitor.namespaces.models', 'App\\Models');
        $basePath = config('janitor.paths.models', app_path('Models'));

        foreach($models as $model => $baseClass) {
            $modelParts = explode('\\', $model);
            $className = array_pop($modelParts);
            $namespace = $baseNamespace;
            $path = $basePath;

            if (count($modelParts) > 0) {
                $namespace .= '\\' . implode('\\', $modelParts);
                $path .= '/' . implode('/', $modelParts);
            }

            // Resolve directory
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Add filename
            $path .= '/' . $className . '.php';

            if (!file_exists($path) || $this->option('o')) {
                $this->stub->create('class', $path, [
                    'namespace' => $namespace,
                    'imports'   => [
                        $baseClass
                    ],
                    'class'     => $className,
                    'extends'   => $baseClass
                ]);
            }
        }
    }

    protected function publishControllers( array $controllers ) {
        $baseNamespace = config('janitor.namespaces.controllers', 'App\\Http\\Controllers');
        $basePath = config('janitor.paths.controllers', app_path('Http/Controllers'));

        foreach($controllers as $controller => $baseClass) {
            $controllerParts = explode('\\', $controller);
            $className = array_pop($controllerParts);
            $namespace = $baseNamespace;
            $path = $basePath;

            if (count($controllerParts) > 0) {
                $namespace .= '\\' . implode('\\', $controllerParts);
                $path .= '/' . implode('/', $controllerParts);
            }

            // Resolve directory
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Add filename
            $path .= '/' . $className . '.php';

            if (!file_exists($path) || $this->option('o')) {
                $this->stub->create('class', $path, [
                    'namespace' => $namespace,
                    'imports'   => [
                        $baseClass
                    ],
                    'class'     => $className,
                    'extends'   => $baseClass
                ]);
            }
        }
    }
}
