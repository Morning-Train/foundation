<?php

return [

    'users'     => [

        // Routing
        'prefix'    => 'users',

        // User friendly name
        'label'     => 'Users',

        // Page titles
        'title'     => [
            'index'     => 'Users',
            'create'    => 'Create user',
            'edit'      => 'Edit user'
        ]

    ],

    'common'    => [

        'routes'        => [
            'index'     => '',
            'create'    => 'create',
            'edit'      => ':id',
            'store'     => ':id',
            'delete'    => ':id/delete'
        ],

        'buttons'       => [
            'create'    => 'Create',
            'update'    => 'Save'
        ]
    ]

];