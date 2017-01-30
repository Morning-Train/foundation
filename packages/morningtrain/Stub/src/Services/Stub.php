<?php

namespace morningtrain\Stub\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;

class Stub
{

    function __construct()
    {
        // Add stub namespace to view
        view()->addNamespace('stubs', base_path('resources/stubs'));
    }

    public function create(string $stub, string $destination, array $params = [])
    {
        /*
         * Setup compile arguments for class
         */

        // class name
        $class = isset($params['class']) && is_string($params['class']) ? $params['class'] : '';

        if (strlen($class) === 0) {
            // Generate an unique name for the class
            $class = 'Class_' . uniqid();
        }

        // namespace
        $namespace = isset($params['namespace']) && is_string($params['namespace']) ? $params['namespace'] : '';
        $params['namespace'] = '';

        if (strlen($namespace) > 0) {
            $params['namespace'] = "namespace $namespace;";
        }

        // imports
        $imports = isset($params['imports']) && is_array($params['imports']) ? $params['imports'] : [];
        $imports = $this->resolveImportConflicts($class, $imports);
        $params['imports'] = '';

        foreach ($imports as $key => $import) {
            $params['imports'] .= 'use ' . (is_string($key) ? "$key as $import" : "$import") . ';' . PHP_EOL;
        }

        // extends
        $extends = isset($params['extends']) && is_string($params['extends']) ? $params['extends'] : '';
        $params['extends'] = '';

        if (strlen($extends) > 0) {
            $params['extends'] .= ' extends ' . implode('', $this->getNormalizedImportClasses([$extends], $imports));
        }

        // implements
        $implements = isset($params['implements']) && is_array($params['implements']) ? $params['implements'] : [];
        $params['implements'] = '';

        if (count($implements) > 0) {
            $params['implements'] = ' implements ' . implode(', ',
                    $this->getNormalizedImportClasses($implements, $imports));
        }

        // traits
        $traits = isset($params['uses']) && is_array($params['uses']) ? $params['uses'] : [];
        $params['uses'] = '';

        if (count($traits) > 0) {
            $params['uses'] = 'use ' . implode(', ', $this->getNormalizedImportClasses($traits, $imports)) . ';';
        }

        /*
         * Compile
         */

        $rendered = view("stubs::$stub", $params)->render();
        file_put_contents($destination, '<?php' . PHP_EOL . PHP_EOL . $rendered);
    }

    /*
     * Helpers
     */

    protected function getNormalizedImportClasses(array $classes, array $imports)
    {
        $normalizedClasses = [];

        foreach ($classes as $class) {
            // Class has an alias
            if (isset($imports[$class])) {
                $normalizedClasses[] = $imports[$class];
            } // Class is imported
            else {
                if (in_array($class, $imports)) {
                    $classParts = explode('\\', $class);
                    $normalizedClasses[] = end($classParts);
                } // Class as raw class name
                else {
                    $normalizedClasses[] = $class;
                }
            }
        }

        return $normalizedClasses;
    }

    protected function resolveImportConflicts($className, array $imports)
    {
        $resolved = [];

        foreach ($imports as $key => $import) {
            $importSegments = explode('\\', $import);
            $importClass = end($importSegments);

            if ($className !== $importClass) {
                $resolved[$key] = $import;
                continue;
            }

            $importClass = "Base$importClass";

            if (is_int($key)) {
                $resolved[$import] = $importClass;
            } else {
                $resolved[$key] = $importClass;
            }
        }

        return $resolved;
    }

}