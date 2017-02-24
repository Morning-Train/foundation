<?php

namespace morningtrain\Janitor\Extensions;

trait Dispatcher
{

    protected $listeners = [];

    public function on($eventName, \Closure $callback)
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = [];
        }

        $this->listeners[$eventName][] = $callback;

        return $this;
    }

    public function off($eventName, \Closure $callback = null)
    {
        if (is_null($callback)) {
            unset($this->listeners[$eventName]);
            return $this;
        }

        if (isset($this->listeners[$eventName])) {
            $index = array_search($callback, $this->listeners[$eventName]);

            if ($index) {
                array_splice($this->listeners[$eventName], $index, 1);
            }
        }

        return $this;
    }

    public function trigger($eventName)
    {
        $args = array_slice(func_get_args(), 1);

        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $callback) {
                call_user_func_array($callback, $args);
            }
        }

        return $this;
    }

}