<?php

namespace morningtrain\Crud\Contracts;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use morningtrain\Crud\Components\Field;
use morningtrain\Crud\Components\Filter;
use morningtrain\Crud\Components\Store;
use morningtrain\Crud\Components\ViewHelper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct()
    {

        // Create store
        $this->store = new Store($this->model, [
            'pagination' => [
                'limit' => $this->paginationLimit,
            ],
        ]);

        // Guess namespace
        if (!isset($this->namespace)) {
            $this->namespace = $this->store->getPluralName();
        }

        // Guess current slug
        if (!isset($this->currentSlug)) {
            $currentRoute = request()->route()->getName();
            $currentRouteParts = explode('.', $currentRoute);
            $this->currentSlug = end($currentRouteParts);
        }

        // Setup base route
        if (!isset($this->baseRoute)) {
            // Guess base route
            $currentRoute = request()->route()->getName();
            $currentRouteParts = explode('.', $currentRoute);
            array_pop($currentRouteParts);

            $this->baseRoute = implode('.', $currentRouteParts);
        }

        // View helper
        $model = $this->model;
        $dummyModel = new $model;

        $this->viewHelper = new ViewHelper([
            'namespace' => $this->namespace,
            'viewNamespace' => $this->viewNamespace,
            'baseRoute' => $this->baseRoute,
            'slug' => $this->currentSlug,
            'singularName' => $dummyModel->getShortName(),
            'pluralName' => $dummyModel->getPluralName(),
        ]);

        // Create index columns
        $this->indexColumns = collect($this->generateIndexColumns($this->viewHelper));

        // Create index filters
        $this->indexFilters = collect($this->generateIndexFilters($this->viewHelper));

        // Create form fields
        $this->formFields = collect($this->generateFormFields($this->viewHelper));

        // Assign index columns and fields to the view helper
        $this->viewHelper->options->set([
            'columns' => $this->indexColumns,
            'fields' => $this->formFields,
            'filters' => $this->indexFilters->where('renderable', true)
        ]);

        // Register filters
        $this->registerIndexFilters();

        // Share view data
        $this->shareViewData();

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

    /**
     * @var string
     */
    protected $namespace;

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
     * @param ViewHelper $crud
     *
     * @return array
     */
    protected function generateIndexColumns(ViewHelper $crud)
    {
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
     * @var array
     */
    protected $postUpdate = [];

    /**
     * Generates and returns the form fields
     * @param ViewHelper $crud
     *
     * @return array
     */
    protected function generateFormFields(ViewHelper $crud)
    {
        return [];
    }

    /**
     * Sets model attributes based on fields
     *
     * @param Request $request
     * @param Model $resource
     */
    protected function setFields(Request $request, Model $resource)
    {
        /**
         * @var Field $field
         */
        foreach ($this->formFields as $field) {
            $status = $field->update($resource, $request);

            if ($status instanceof \Closure) {
                $this->postUpdate[] = [
                    'field' => $field,
                    'callback' => $status
                ];
            } else {
                if (!is_null($status)) {
                    return $status;
                }
            }
        }
    }

    protected function postUpdateFields(Request $request, Model $resource)
    {
        foreach ($this->postUpdate as $postUpdate) {
            $field = $postUpdate['field'];
            $callback = $postUpdate['callback'];

            $status = $callback($field, $resource, $request);

            if (!is_null($status)) {
                return $status;
            }
        }
    }

    /*
    * ------------------------------------------------
    * 			    Filters hook
    * ------------------------------------------------
    */

    /**
     * @var Collection
     */
    protected $indexFilters;

    /**
     * Generates and returns the form fields
     * @param ViewHelper $crud
     *
     * @return array
     */
    protected function generateIndexFilters(ViewHelper $crud)
    {
        return [];
    }

    protected function registerIndexFilters()
    {
        foreach ($this->indexFilters as $filter) {
            $this->store->addFilter(function ($query, Request $request) use ($filter) {
                return $filter->apply($query, $request);
            });
        }
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
     * @var string
     */
    protected $currentSlug;

    /**
     * @var ViewHelper
     */
    protected $viewHelper;

    /**
     * @param string $messageKey
     * @return $this
     */
    protected function notify(string $messageKey)
    {
        session()->flash('status', trans($this->viewHelper->trans("messages.$messageKey")));

        return $this;
    }

    /**
     * @param $viewName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function view(string $viewName)
    {
        return view(strlen($this->viewNamespace) > 0 ? $this->viewNamespace . '.' . $viewName : $viewName);
    }

    protected function shareViewData()
    {
        view()->share([
            'crud' => $this->viewHelper,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function redirectToBaseRoute()
    {
        return redirect(route($this->baseRoute . '.index'));
    }

    /**
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToBaseRouteWithError($message)
    {
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
    protected function rules(Request $request, Model $resource)
    {
        $rules = [];

        foreach ($this->formFields as $field) {
            $rules = array_merge($rules, $field->rules($resource, $request));
        }

        return $rules;
    }


    /*
	 * ------------------------------------------------
	 * 			    Action hooks
	 * ------------------------------------------------
	 */

    /**
     * @param Model $resource
     */
    protected function beforeStore(Model $resource)
    {
    }

    /**
     * @param Model $resource
     */
    protected function afterStore(Model $resource)
    {
    }

    /**
     * @param Model $resource
     */
    protected function beforeDestroy(Model $resource)
    {
    }

    /**
     * @param Model $resource
     */
    protected function afterDestroy(Model $resource)
    {
    }

    /**
     * After constructor
     */
    protected function boot()
    {
    }

    /*
	 * ------------------------------------------------
	 * 			    Main route callbacks
	 * ------------------------------------------------
	 */

    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        return $this->view('crud.index')->with('entries', $this->store->all())->render();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function show(Request $request, $id)
    {
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
    public function create(Request $request)
    {
        return $this->view('crud.form')->with('entry', $this->store->one())->render();
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(Request $request, $id = null)
    {

        // Get resource
        $resource = $this->store->one($id);

        // Validate request
        $this->validate($request, $this->rules($request, $resource));

        // Before store hook
        $status = $this->beforeStore($resource);

        if (!is_null($status)) {
            return $status;
        }

        // Update attributes
        $status = $this->setFields($request, $resource);

        if (!is_null($status)) {
            return $status;
        }

        // Save
        $resource->save();

        // Post update
        $this->postUpdateFields($request, $resource);

        if (!is_null($status)) {
            return $status;
        }

        // Call hook
        $this->afterStore($resource);

        // Notify
        $this->notify($resource->wasNew() ? 'created' : 'updated');

        return $this->redirectToBaseRoute();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, $id)
    {
        $resource = $this->store->one($id);

        if (is_null($resource)) {
            return response()->make('Not found', 404);
        }

        // Call before hook
        $status = $this->beforeDestroy($resource);

        if (!is_null($status)) {
            return $status;
        }

        // Delete the resource
        $resource->delete();

        // Call after hook
        $this->afterDestroy($resource);

        // Notify
        $this->notify('deleted');

        return $this->redirectToBaseRoute();
    }
}