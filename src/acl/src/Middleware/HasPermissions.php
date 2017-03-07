<?php

namespace morningtrain\Acl\Middleware;

use Illuminate\Contracts\Auth\Access\Gate;

class HasPermissions
{

    /**
     * @var Gate
     */
    protected $gate;

    function __construct()
    {
        $this->gate = app()->make(Gate::class);
    }

    public function handle($request, \Closure $next)
    {
        $action = $request->route()->getAction();

        if (isset($action['permissions']) && is_array($action['permissions'])) {
            foreach ($action['permissions'] as $permission) {
                if ($this->gate->denies($permission)) {
                    abort(403, 'Forbidden');
                }
            }
        }

        return $next($request);
    }

}
