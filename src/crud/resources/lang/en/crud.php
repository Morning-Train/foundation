<?php

return [

    /*
     * Common translations (applies to all models unless overwritten)
     */

    'common' => [

        'routes' => [
            'index' => '',
            'create' => 'create',
            'edit' => ':id',
            'store' => ':id',
            'delete' => ':id/delete',
        ],

        'buttons' => [
            'create' => 'Create',
            'update' => 'Save',
            'filter' => 'Filter'
        ],

        // Page titles
        'title' => [
            'index' => ':type',
            'create' => 'Create :type',
            'edit' => 'Edit :type',
        ],

        // Actions
        'actions' => [
            'create' => 'Create :type',
            'edit' => 'Edit :type',
            'delete' => 'Delete :type',
        ],

        // Messages
        'messages' => [
            'delete-confirmation' => 'Are you sure you want to delete this :type ?',
            'created' => 'The :type was created!',
            'updated' => 'The :type was updated!',
            'deleted' => 'The :type was deleted!'
        ],

        // Fields
        'fields' => [
            'sample' => [
                'label' => 'Sample',
                'placeholder' => 'Sample placeholder'
            ]
        ],

        // Columns
        'columns' => [
            'id' => '#',
            'actions' => 'Actions'
        ]
    ],

    /*
     * Specify routing paths and friendly names or override common translations for
     * a particular model as shown in the example below
     */

    'users' => [

        // Routing
        'prefix' => 'users',

        // User friendly name
        'label' => 'Users'

    ],

];