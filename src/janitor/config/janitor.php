<?php

return [

    'namespaces' => [
        'models'      => 'App\Models',
        'controllers' => 'App\Http\Controllers',
    ],

    'paths' => [
        'models'      => app_path('Models'),
        'controllers' => app_path('Http\\Controllers'),
        'migrations'  => database_path('migrations'),
    ],

    'routing' => [
        'groups' => [

        ],
    ],

];