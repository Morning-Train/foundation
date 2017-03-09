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
            return redirect(route(config('acl.routes.login', 'auth.login')));
        }

        if (isset($action['roles'])) {
            $role = is_array($action['roles']) ? $action['roles'] : [$action['roles']];
            $match = isset($action['match-roles']) ? $action['match-roles'] : 'all';

            if ((count($role) > 0) && !$this->getStatus($user, $role, $match)) {
                return abort(403, 'Forbidden');
            }
        }

        return $next($request);
    }

    protected function getStatus($user, array $roles, $match = 'all')
    {
        switch ($match) {
            case 'one':
                $status = $user->isAssignedEither($roles);
                break;

            default:
                $status = $user->isAssigned($roles);
                break;
        }

        return $status;
    }

}
