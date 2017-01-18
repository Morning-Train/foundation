<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Collective\Html\FormFacade as Form;
use morningtrain\Crud\Components\Field;
use morningtrain\Crud\Components\Column;
use morningtrain\Crud\Components\Filter;
use morningtrain\Crud\Contracts\Controller;
use Illuminate\Http\Request;
use morningtrain\Crud\Contracts\Model;


class PostsController extends Controller {
    

    
    /*
    * ------------------------------------------------
    * 			    Store options
    * ------------------------------------------------
    */

    /**
    * @var  string
    */
    protected $model = \App\Models\Post::class;

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
        $crud = $this->viewHelper;

        return [
            Column::create([
                'name'      => 'id',
                'label'     => '#'
            ]),

            Column::create([
                'name'      => 'title',
                'label'     => 'Title'
            ]),

            Column::actions([
                'label'     => 'Actions'
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
                'name'          => 'title',
                'rules'         => 'required',
                'attributes'    => [
                    'placeholder'   => 'Enter the title'
                ],
                'params'        => [
                    'extraParam'       => 'myExtraParam'
                ]
            ]),

            Field::text([
                'name'          => 'content',

                // Validation hook
                'rules'         => function( Post $post, Request $request ) {
                    return $post->isNew() ?
                        [ 'content' => 'required' ] :
                        [];
                },

                // Update hook
                'update'        => function( Post $post, Request $request ) {
                    $post->content = nl2br($request->get('content'));
                },

                'attributes'    => [
                    'placeholder'   => 'Enter the contents'
                ],
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
    protected function beforeDestroy(Model $resource) {}

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

        $this->store->addFilter('title', function( $query, $search ) {
             $query->where('title', 'LIKE', "%$search%");
        });
    }

}