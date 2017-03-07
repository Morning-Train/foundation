<?php

namespace morningtrain\Acl\Middleware;

use Illuminate\Contracts\Auth\Factory as Auth;

class IsAssigned
{

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, \Closure $next)
    {
        $user = $this->auth->user();
        $action = $request->route()->getAction();

        if (is_null($user)) {
            abort(401, 'Unauthorized');
        }

        if (isset($action['role'])) {
            $role = is_array($action['role']) ? $action['role'] : [$action['role']];

            if ((count($role) > 0) && !$user->isAssigned($role)) {
                abort(403, 'Forbidden');
            }
        }

        return $next($request);
    }

}
