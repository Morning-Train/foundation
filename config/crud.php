<?php

return [

    'namespaces'    => [
        'models'        => 'App\Models',
        'controllers'   => 'App\Http\Controllers'
    ],

    'paths'         => [
        'models'        => app_path('Models'),
        'controllers'   => app_path('Http\\Controllers')
    ],

    'base-classes'     => [
        'model'         => \morningtrain\Crud\Contracts\Model::class,
        'controller'    => \morningtrain\Crud\Contracts\Controller::class
    ],

    /*
     * Extra configuration bundles
     * ------------------------------------------
     *
     * You can specify a configuration namespace on crud:new by passing the option --config=crud.extras.customConfig
     *
     */

    'extras'        => [
        'customConfig'  => [
            'namespaces'    => [
                'models'    => 'App\Models\Custom'
            ]
        ]
    ]

];