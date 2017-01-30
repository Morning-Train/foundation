<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use morningtrain\Crud\Components\ViewHelper;
use morningtrain\Crud\Contracts\Controller;

use Illuminate\Http\Request;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Crud\Components\Filter;
use morningtrain\Crud\Components\Column;
use morningtrain\Crud\Components\Field;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    
    
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
     * @param ViewHelper $crud
     *
     * @return array
     */
    protected function generateIndexColumns(ViewHelper $crud)
    {
        return [
            Column::create([
                'name'      => 'id',
                'label'     => $crud->trans('columns.id'),
                'order'     => 'asc'    // default order on columns
            ]),

            Column::create([
                'name'  => 'name',
                'label' => 'Name',
            ]),

            Column::userActions([
                'name'      => 'actions',
                'label'     => $crud->trans('columns.actions'),
                'class'     => 'align-right',
                'sortable'  => false
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
     * @param ViewHelper $crud
     *
     * @return array
     */
    protected function generateFormFields(ViewHelper $crud)
    {
        return [
            Field::input([
                'name'       => 'name',
                'label'      => 'Name',
                'rules'      => 'required',
                'attributes' => [
                    'placeholder' => 'Enter the name',
                ],
            ]),

            Field::input([
                'type'  => 'email',
                'name'  => 'email',
                'label' => 'Email address',

                'rules' => function (User $user, Request $request) {
                    return $user->isNew() ? 'required|email|unique:users,email' : 'required|email|unique:users,email,' . $user->id;
                },

                'attributes' => [
                    'placeholder' => 'Enter the email address',
                ],
            ]),
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
    protected function beforeStore(Model $resource)
    {
        if ($resource->isNew()) {
            $resource->password = Hash::make(strtolower(request()->get('name')));
        }
    }

    /**
     * @param  Model $resource
     */
    protected function afterStore(Model $resource)
    {

    }

    /**
     * @param  Model $resource
     */
    protected function beforeDestroy(Model $resource)
    {
        if ($resource->id === Auth::user()->id) {
            return response()->make('Forbidden', 403);
        }
    }

    /**
     * @param  Model $resource
     */
    protected function afterDestroy(Model $resource)
    {

    }

    /**
     * After constructor
     */
    protected function boot()
    {
        // Register filters
        $this->store->addFilter('order', Filter::order($this->indexColumns));
    }

}