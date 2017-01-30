<?php

return [

    /*
     * Admin general translations
     * --------------------------
     */

    'prefix' => 'admin',

    /*
     * Authentication routes translations
     * ----------------------------------
     */

    'auth' => [

        'prefix' => 'auth',

        'fields' => [
            'email'                 => 'Enter your email address',
            'password'              => 'Enter your password',
            'password_confirmation' => 'Confirm your password',
            'name'                  => 'Enter your name',
        ],

        'actions' => [
            'forgot-password' => 'Forgot your password ?',
            'back-to-login'   => 'Back to login page',
            'register'        => 'Create an account',
        ],

        'routes' => [

            'login' => [
                'title'  => 'Login',
                'path'   => 'login',
                'submit' => 'Login',
            ],

            'register' => [
                'title'  => 'Create an account',
                'path'   => 'register',
                'submit' => 'Register',
            ],

            'forgot-password' => [
                'title'  => 'Forgot your password ?',
                'path'   => 'forgot-password',
                'submit' => 'Send',
            ],

            'reset-password' => [
                'title'  => 'Reset password',
                'path'   => 'reset-password/:token',
                'submit' => 'Reset',
            ],

            'logout' => [
                'path' => 'logout',
            ],

        ],
    ],


];