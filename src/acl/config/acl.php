<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'routes' => [
        'login' => 'auth.login'
    ],

    /*
    |--------------------------------------------------------------------------
    | List af roles
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'roles' => [

        'developer' => [
            'name' => 'Developer',
            'super' => true,
            'protected' => true,
            'permissions' => [

            ],
        ],

        'admin' => [
            'name' => 'Administrator',
            'super' => true,
            'protected' => true,
            'permissions' => [

            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | List of permissions
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'permissions' => [

        // Access permissions
        'access' => [

        ],

    ],

];