<?php

return [

    'namespaces'    => [
        'models'        => 'App\Models',
        'controllers'   => 'App\Http\Controllers'
    ],

    'paths'         => [
        'models'        => app_path('Models'),
        'controllers'   => app_path('Http\\Controllers'),
        'migrations'    => database_path('migrations')
    ],

    'base-classes'     => [
        'model'         => \Illuminate\Database\Eloquent\Model::class,
        'controller'    => \App\Http\Controllers\Controller::class
    ]
];