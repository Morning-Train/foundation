<?php

namespace morningtrain\Themer\Middleware;

use Closure;
use morningtrain\Themer\Services\Themer;

class LoadTheme {

    /**
     * @var Themer
     */
    protected $themer;

    function __construct( Themer $themer ) {
        $this->themer = $themer;
    }

    public function handle($request, Closure $next) {
        $action = $request->route()->getAction();

        // Determine requested theme
        $requestedTheme = isset($action['theme']) ? $action['theme'] : null;

        if (is_array($requestedTheme)) {
            $requestedTheme = array_pop($requestedTheme);
        }

        $themeName = is_string($requestedTheme) && (strlen($requestedTheme) > 0) ?
            $requestedTheme :
            config('themer.default', 'Base');

        // Load theme
        $this->themer->load($themeName);

        return $next($request);
    }

}
