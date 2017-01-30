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
        'no-access' => 'auth.logout',
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
            'name'        => 'Developer',
            'super'       => true,
            'permissions' => [

            ],
        ],

        'admin' => [
            'name'        => 'Administrator',
            'super'       => true,
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