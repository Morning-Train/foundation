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
        'model'         => \morningtrain\Crud\Contracts\Model::class,
        'controller'    => \morningtrain\Crud\Contracts\Controller::class
    ]
];