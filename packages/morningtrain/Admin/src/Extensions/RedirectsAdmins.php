<?php

namespace morningtrain\Admin\Extensions;

trait RedirectsAdmins
{

    protected function redirectAdmin($guard)
    {
        if (isset($guard) && ($user = $guard->user()) && $user->allowed('access.admin')) {
            return route('admin');
        }

        return false;
    }

}