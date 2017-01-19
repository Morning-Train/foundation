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

    'routes'    => [
        'no-access'         => 'auth.logout'
    ],

    /*
    |--------------------------------------------------------------------------
    | List af roles
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'roles'     => [

        'developer' => [
            'name'          => 'Developer',
            'super'         => false,
            'permissions'   => [
                'test.withName',
                'test.withoutName'
            ]
        ],

        'admin' => [
            'name'          => 'Administrator',
            'super'         => true,
            'permissions'   => [
                'test.withName',
                'test.withoutName'
            ]
        ],

        'company-admin' => [
            'name'          => 'Company admin',
            'super'         => false,
            'permissions'   => [
                'test.company'
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | List of permissions
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'permissions'   => [
        'test'  => [
            'withName'  => 'Permission name',
            'withoutName',
            'company'
        ]
    ]

];