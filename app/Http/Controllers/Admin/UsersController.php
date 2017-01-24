<?php

namespace App\Http\Controllers\Admin;

use morningtrain\Crud\Contracts\Controller;

use Illuminate\Http\Request;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Crud\Components\Filter;
use morningtrain\Crud\Components\Column;
use morningtrain\Crud\Components\Field;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller {
    
    
    
    /*
    * ------------------------------------------------
    * 			    Store options
    * ------------------------------------------------
    */

    /**
    * @var  string
    */
    protected $model = \App\Models\User::class;

    /**
    * @var  int
    */
    protected $paginationLimit = 10;

    /*
    * ------------------------------------------------
    * 			    Index columns hooks
    * ------------------------------------------------
    */

    /**
    * Generates and returns the index columns
    *
    * @return  array
    */
    protected function generateIndexColumns() {
        return [
            Column::create([
                'name'      => 'id',
                'label'     => '#'
            ]),

            Column::create([
                'name'      => 'name',
                'label'     => 'Name'
            ]),

            Column::userActions([
                'name'      => 'actions',
                'label'     => 'Actions',
                'class'     => 'align-right'
            ])
        ];
    }

    /*
    * ------------------------------------------------
    * 			    Form fields hook
    * ------------------------------------------------
    */

    /**
    * Generates and returns the form fields
    *
    * @return  array
    */
    protected function generateFormFields() {
        return [
            Field::text([
                'name'          => 'name',
                'label'         => 'Name',
                'attributes'    => [
                    'placeholder'   => 'Enter the name'
                ]
            ])
        ];
    }

    /*
    * ------------------------------------------------
    * 			    Action hooks
    * ------------------------------------------------
    */

    /**
    * @param  Model $resource
    */
    protected function beforeStore(Model $resource) {}

    /**
    * @param  Model $resource
    */
    protected function afterStore(Model $resource) {
        // notify here instead with session->put()
    }

    /**
    * @param  Model $resource
    */
    protected function beforeDestroy(Model $resource) {
        if ($resource->id === Auth::user()->id) {
            return response()->make('Forbidden', 403);
        }
    }

    /**
    * @param  Model $resource
    */
    protected function afterDestroy(Model $resource) {
        // notify here instead with session->put()
    }

    /**
    * After constructor
    */
    protected function boot() {
        // Register filters
        $this->store->addFilter('order', Filter::order($this->indexColumns));
    }

}