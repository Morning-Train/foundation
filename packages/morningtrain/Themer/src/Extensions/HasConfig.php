<?php

namespace morningtrain\Themer\Extensions;

use Illuminate\Config\Repository;

trait HasConfig {

    /**
     * @var Repository
     */
    protected $config;

    /*
     * Config accessors and mutators
     */

    public function get( $key, $default = null ) {
        return $this->config->get($key, $default);
    }

    public function set( $key, $value = null ) {
        $this->config->set($key, $value);
        return $this;
    }

    /*
     * Registration
     */

    protected function registerConfig() {
        // Initialization
        if (!isset($this->config)) {
            $this->config = new Repository(config('themer.config.' . $this->name, []));
        }
    }

}