<?php

namespace morningtrain\Acl\Middleware;

use Illuminate\Contracts\Auth\Access\Gate;

class HasAccess
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

        if (isset($action['as'])) {
            if ($this->gate->denies('access.' . $action['as'])) {
                abort(403, 'Forbidden');
            }
        }

        return $next($request);
    }

}
