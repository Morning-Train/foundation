<?php

namespace morningtrain\Janitor\Middleware;

use Illuminate\Http\Request;
use morningtrain\Janitor\Exceptions\JanitorException;

class GlobalMiddleware {

    /**
     * @var array
     */
    protected static $middleware = [];

    /**
     * Appends a middleware to stack
     *
     * @param $middleware
     */
    public static function append( $middleware ) {
        if (is_array($middleware)) {
            foreach($middleware as $mw) {
                static::append($mw);
            }

            return;
        }

        if (array_search($middleware, static::$middleware) === false) {
            static::$middleware[] = $middleware;
        }
    }

    /**
     * Prepends a middleware to stack
     *
     * @param $middleware
     */
    public static function prepend( $middleware ) {
        if (is_array($middleware)) {
            foreach($middleware as $mw) {
                static::prepend($mw);
            }
        }

        if (array_search($middleware, static::$middleware) === false) {
            array_unshift(static::$middleware, $middleware);
        }
    }

    /*
     * Stack handler
     */

    public function handle( Request $request, \Closure $next ) {
        $this->index = -1;
        $this->terminate = $next;

        return $this->next($request);
    }

    /*
     * Iteration helpers
     */

    /**
     * @var \Closure
     */
    protected $terminate;

    /**
     * @var int
     */
    protected $index;

    protected function next( $request ) {
        $this->index++;

        if ($this->index === count(static::$middleware)) {
            $terminate = $this->terminate;
            return $terminate($request);
        }

        $middlewareClass = static::$middleware[ $this->index ];
        $middleware = new $middlewareClass();

        if (!is_callable([ $middleware, 'handle' ])) {
            throw new JanitorException("Global middleware `$middlewareClass` is invalid.");
        }

        return $middleware->handle($request, \Closure::bind(function( $request ) {
            return $this->next($request);

        }, $this, static::class));
    }

}