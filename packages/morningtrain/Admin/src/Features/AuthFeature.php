<?php

namespace morningtrain\Admin\Features;

use morningtrain\Janitor\Contracts\JanitorFeature;
use Illuminate\Routing\Router;

class AuthFeature extends JanitorFeature
{

    protected $routerGroup = 'auth';

    protected function routerOptions()
    {
        return [
            'prefix'     => trans('admin.auth.prefix'),
            'middleware' => 'web',
            'theme'      => 'Admin',
        ];
    }

    protected function routes(Router $router)
    {

        // Main route
        $router->get('', [
            'as'   => 'auth',
            'uses' => function () {
                return redirect(route('auth.login'));
            },
        ]);

        // Login route
        $router->get(trans('admin.auth.routes.login.path'), [
            'as'   => 'auth.login',
            'uses' => 'LoginController@showLoginForm',
        ]);

        $router->post(trans('admin.auth.routes.login.path'), [
            'as'   => 'auth.login.do',
            'uses' => 'LoginController@login',
        ]);

        // Register route
        if (config('admin.auth.registration', true) === true) {

            $router->get(trans('admin.auth.routes.register.path'), [
                'as'   => 'auth.register',
                'uses' => 'RegisterController@showRegistrationForm',
            ]);

            $router->post(trans('admin.auth.routes.register.path'), [
                'as'   => 'auth.register.do',
                'uses' => 'RegisterController@register',
            ]);
        }

        // Forgot password route
        $router->get(trans('admin.auth.routes.forgot-password.path'), [
            'as'   => 'auth.forgot-password',
            'uses' => 'ForgotPasswordController@showLinkRequestForm',
        ]);

        $router->post(trans('admin.auth.routes.forgot-password.path'), [
            'as'   => 'auth.forgot-password.do',
            'uses' => 'ForgotPasswordController@sendResetLinkEmail',
        ]);

        // Reset password route
        $router->get(trans('admin.auth.routes.reset-password.path', ['token' => '{token}']), [
            'as'   => 'auth.reset-password',
            'uses' => 'ResetPasswordController@showResetForm',
        ]);

        $router->post(trans('admin.auth.routes.reset-password.path'), [
            'as'   => 'auth.reset-password.do',
            'uses' => 'ResetPasswordController@reset',
        ]);

        // Logout route
        $router->get(trans('admin.auth.routes.logout.path'), [
            'as'   => 'auth.logout',
            'uses' => 'LoginController@logout',
        ]);

    }

}