<?php

namespace morningtrain\Acl\Middleware;

use Illuminate\Contracts\Auth\Factory as Auth;

class RequireAuthentication
{

    /**
     * @var Auth
     */
    protected $auth;

    function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, \Closure $next)
    {
        if (!$this->auth->check()) {
            return redirect(route(config('acl.routes.login', 'auth.login')));
        }

        return $next($request);
    }

}
