<?php

namespace morningtrain\Crud\Contracts;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Collection;
use morningtrain\Crud\Components\Store;

abstract class Controller extends BaseController {

    function __construct() {

        // Create store
        $this->store = new Store($this->model, [
            'pagination'    => [
                'limit'     => $this->paginationLimit
            ]
        ]);

        // Create index columns
        $this->indexColumns = collect($this->generateIndexColumns());

        // Create form fields
        $this->formFields = collect($this->generateFormFields());

        // Share view data
        $this->shareViewData();

        // Setup base route
        if (!isset($this->baseRoute)) {
            // Guess base route
            $currentRoute = request()->route()->getName();
            $currentRouteParts = explode('.', $currentRoute);
            array_pop($currentRouteParts);
            array_push($currentRouteParts, 'index');

            $this->baseRoute = implode('.', $currentRouteParts);
        }

        // Boot controller
        $this->boot();

    }

    /*
	 * ------------------------------------------------
	 * 			    Store options
	 * ------------------------------------------------
	 */

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var string
     */
    protected $model = Model::class;

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
     * @var Collection
     */
    protected $indexColumns;

    /**
     * Generates and returns the index columns
     *
     * @return array
     */
    protected function generateIndexColumns() {
        return [];
    }

    /*
	 * ------------------------------------------------
	 * 			    Form fields hook
	 * ------------------------------------------------
	 */

    /**
     * @var Collection
     */
    protected $formFields;

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
	 * 			    View and response functionality
	 * ------------------------------------------------
	 */

    /**
     * @var string
     */
    protected $viewNamespace;

    /**
     * @var string
     */
    protected $baseRoute;

    /**
     * @param $viewName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function view(string $viewName) {
        return view(strlen($this->viewNamespace) > 0 ? $this->viewNamespace . '.' . $viewName : $viewName);
    }

    protected function shareViewData() {
        view()->share([
            'namespace', $this->viewNamespace
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function redirectToBaseRoute() {
        return redirect(route($this->baseRoute));
    }

    /**
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToBaseRouteWithError($message) {
        return $this->redirectToBaseRoute()->with('errors', collect($message));
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
     * @param Request $request
     * @param Model $resource
     */
    protected function setAttributes(Request $request, Model $resource) {}

    /**
     * @param Model $resource
     */
    protected function afterStore(Model $resource) {}

    /**
     * @param Model $resource
     */
    protected function beforeDestroy(Model $resource) {}

    /**
     * @param Model $resource
     */
    protected function afterDestroy(Model $resource) {}

    /**
     * After constructor
     */
    protected function boot() {}

    /*
	 * ------------------------------------------------
	 * 			    Main route callbacks
	 * ------------------------------------------------
	 */

    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request) {
        return $this->view('crud.index')->with('entries', $this->store->all())->render();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function show(Request $request, $id) {
        $resource = $this->store->one($id);

        if ($resource->isNew()) {
            return $this->redirectToBaseRoute();
        }

        return $this->view('crud.form')->with('entry', $resource)->render();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request) {
        return $this->view('crud.form')->with('entry', $this->one())->render();
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(Request $request, $id = null) {

        // Get resource
        $resource = $this->one($id);

        // Validate request
        $this->validate($request, $this->rules($request, $resource));

        // Update attributes
        $status = $this->setAttributes($request, $resource);

        if (!is_null($status)) {
            return $status;
        }

        // Save
        $resource->save();

        // Call hook
        $this->afterStore($resource);

        return $this->redirectToBaseRoute();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, $id) {
        $this->one($id, function( $resource ) {

            // Call before hook
            $this->beforeDestroy($resource);

            $resource->delete();

            // Call after hook
            $this->afterDestroy($resource);

        });

        return $this->redirectToBaseRoute();
    }
}