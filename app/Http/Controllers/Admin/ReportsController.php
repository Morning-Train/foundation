<?php

namespace App\Http\Controllers\Admin;

use App\Models\Report;
use App\Models\User;
use morningtrain\Crud\Contracts\Controller;

use Illuminate\Http\Request;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Crud\Components\Filter;
use morningtrain\Crud\Components\Column;
use morningtrain\Crud\Components\Field;
use morningtrain\Crud\Components\ViewHelper;

class ReportsController extends Controller {
    
    
    
    /*
    * ------------------------------------------------
    * 			    Store options
    * ------------------------------------------------
    */

    /**
    * @var  string
    */
    protected $model = \App\Models\Report::class;

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
    protected function generateIndexColumns( ViewHelper $helper ) {
        return [
            Column::create([
                'name'      => 'id',
                'label'     => '#',
                'order'     => 'asc'    // default order on columns
            ]),

            Column::create([
                'name'      => 'name',
                'label'     => 'Name'
            ]),

            Column::create([
                'name'      => 'user',
                'label'     => 'User',
                'sortable'  => true,

                'sort'      => function( $query, $name, $direction ) {
                    $usersTable = (new User())->getTable();
                    $reportsTable = (new Report())->getTable();

                    $query
                        ->join($usersTable, "$usersTable.id", '=', "$reportsTable.user_id")
                        ->select("$reportsTable.*")
                        ->orderBy("$usersTable.name", $direction);
                },

                'render'    => function( Column $column, Report $report ) {
                    return $report->user->name;
                }
            ]),

            Column::actions([
                'name'      => 'actions',
                'label'     => 'Actions',
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
    protected function generateFormFields( ViewHelper $crud ) {
        return [
            Field::input([
                'name'      => 'name',
                'label'     => 'Name',
                'rules'     => 'required'
            ]),

            Field::select([
                'name'      => 'user_id',
                'label'     => 'User',
                'rules'     => 'required',

                'options'   => function( Report $report ) {
                    $users = User::get();
                    $options = [];

                    $users->each(function( $user ) use( &$options ) {
                        $options[$user->id] = $user->name;
                    });

                    return $options;
                }
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

    }

    /**
    * @param  Model $resource
    */
    protected function beforeDestroy(Model $resource) {}

    /**
    * @param  Model $resource
    */
    protected function afterDestroy(Model $resource) {

    }

    /**
    * After constructor
    */
    protected function boot() {
        // Register filters
        $this->store->addFilter('order', Filter::order($this->indexColumns));
    }

}