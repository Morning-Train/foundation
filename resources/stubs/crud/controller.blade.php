@extends('stubs::class')

@section('imports')
    use Illuminate\Http\Request;
    use morningtrain\Crud\Contracts\Model;
    use morningtrain\Crud\Components\Filter;
    use morningtrain\Crud\Components\Column;
    use morningtrain\Crud\Components\Field;
    use morningtrain\Crud\Components\ViewHelper;
@stop

@section('body')

    /*
    * ------------------------------------------------
    *                Store options
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
    *                Index columns hooks
    * ------------------------------------------------
    */

    /**
    * Generates and returns the index columns
    * @param ViewHelper $crud
    *
    * @return array
    */
    protected function generateIndexColumns( ViewHelper $crud ) {
    return [
    Column::create([
    'name'      => 'id',
    'label'     => '#',
    'order'     => 'asc'    // default order on columns
    ])
    ];
    }

    /*
    * ------------------------------------------------
    *                Form fields hook
    * ------------------------------------------------
    */

    /**
    * Generates and returns the form fields
    * @param ViewHelper $crud
    *
    * @return array
    */
    protected function generateFormFields( ViewHelper $crud ) {
    return [];
    }

    /*
    * ------------------------------------------------
    *                Action hooks
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

    }

    /**
    * @param Model $resource
    */
    protected function beforeDestroy(Model $resource) {}

    /**
    * @param Model $resource
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

@stop