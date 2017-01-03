<?php

namespace morningtrain\Themer\Extensions;

trait HasConfig {

    /**
     * @var array
     */
    protected $config;

    /*
     * Config accessors and mutators
     */

    public function get( $key = null, $default = null ) {
        if (is_null($key)) {
            return $this->config;
        }

        // Query the key
        $query = explode('.', $key);
        $current = $this->config;

        while(count($query) > 0) {
            $currentKey = array_shift($query);
            $current = $current[$currentKey];

            if (is_null($current)) {
                return $default;
            }
        }

        return $current;
    }

    public function set( $key, $value = null ) {
        if (is_array($key)) {
            foreach($key as $keyName => $value) {
                $this->set($keyName, $value);
            }

            return $this;
        }

        // Set the value by query
        $query = explode('.', $key);
        $current = $this->config;

        while(count($query) > 0) {
            $currentKey = array_shift($query);

            if (count($query) === 0) {
                $current[$currentKey] = $value;
            }
            else {
                if (!is_array($current[$currentKey])) {
                    $current[$currentKey] = [];
                }

                $current = $current[$currentKey];
            }

        }

        return $this;
    }

    /*
     * Registration
     */

    protected function registerConfig() {
        // Initialization
        if (!isset($this->config)) {
            $this->config = [];
        }
    }

}