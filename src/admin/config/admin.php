<?php

return [

    /*
     * Base route (redirection after login - leave null for auto)
     */

    'baseRoute' => null,

    /*
     * Authentication configuration
     */

    'auth' => [
        'registration' => true,
    ],

    /*
     * CRUD routes are registered here and they will automatically be added to the router
     * and shown in the menu. (the order reflects in the menu as well)
     */

    'items' => [

        \App\Models\User::class => [
            'icon' => '&#xE7FB;',
        ],

    ],

    /*
     * CRUD settings when creating crud bundles for the admin panel
     *
     * e.g. php artisan crud:new MyModel --config=admin.crud
     */

    'crud' => [

        'namespaces' => [
            'controllers' => 'App\\Http\\Controllers\\Admin',
        ],

        'paths' => [
            'controllers' => app_path('Http/Controllers/Admin'),
        ],

    ],

];