<?php

namespace morningtrain\Themer\Middleware;

use Closure;

class LoadTheme {

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
        app()->make('Themer')->load($themeName);

        return $next($request);
    }

}
