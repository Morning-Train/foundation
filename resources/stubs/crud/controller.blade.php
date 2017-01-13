@extends('stubs::class')

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
    * 			    Validation hook
    * ------------------------------------------------
    */

    /**
    * @param Request $request
    * @param Model $resource
    * @return array
    */
    protected function rules(Request $request, Model $resource) {
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
        $columns = $this->indexColumns;

        // Register filters
        $this->store->addFilter('order', function($query, $name) use($columns) {
            // Find column
            $column = $columns->where('name', $name)->first();

            if (isset($column) && $column->sortable) {
                $direction = request()->get('dir', 'asc');
                $column->order = $direction;

                $query->orderBy($name, $direction);
            }
        });
    }

@stop