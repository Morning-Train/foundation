<?php

namespace morningtrain\Janitor\Helper;

class MigrationHelper
{

    /**
     * @var string
     */
    protected $basepath;

    function __construct()
    {
        $this->basepath = config('janitor.paths.migrations', database_path('migrations'));
    }

    public function path(string $path)
    {
        return $this->basepath . '/' . $path;
    }

    public function filename(string $name)
    {
        return date('Y_m_d_His_') . $name . '.php';
    }

    public function nameFromClass($class)
    {
        $classSegments = explode('\\', $class);
        $className = end($classSegments);

        return strtolower(preg_replace('/\B([A-Z])/', '_$1', $className));
    }

    public function exists(string $name)
    {
        $dh = opendir($this->basepath);

        while (($filename = readdir($dh)) !== false) {
            if (strpos($filename, $name) !== false) {
                return $filename;
            }
        }

        return false;
    }

}