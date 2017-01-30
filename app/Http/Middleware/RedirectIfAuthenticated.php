<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use morningtrain\Admin\Extensions\RedirectsAdmins;

class RedirectIfAuthenticated
{
    use RedirectsAdmins;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return $this->redirectAdmin(Auth::guard($guard)) ?: '/';
        }

        return $next($request);
    }
}
