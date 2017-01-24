@extends('stubs::class')

@section('imports')
use Illuminate\Http\Request;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Crud\Components\Filter;
use morningtrain\Crud\Components\Column;
use morningtrain\Crud\Components\Field;
@stop

@section('body')

    /*
    * ------------------------------------------------
    * 			    Store options
    * ------------------------------------------------
    */

    /**
    * @var string
    */
    protected $model = {!! $model !!};

    /**
    * @var int
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
    * @return array
    */
    protected function generateIndexColumns() {
        return [
            Column::create([
                'name'      => 'id',
                'label'     => '#'
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
    * @return array
    */
    protected function generateFormFields() {
        return [];
    }

    /*
    * ------------------------------------------------
    * 			    Action hooks
    * ------------------------------------------------
    */

    /**
    * @param Model $resource
    */
    protected function beforeStore(Model $resource) {}

    /**
    * @param Model $resource
    */
    protected function afterStore(Model $resource) {
        // notify here instead with session->put()
    }

    /**
    * @param Model $resource
    */
    protected function beforeDestroy(Model $resource) {}

    /**
    * @param Model $resource
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

@stop