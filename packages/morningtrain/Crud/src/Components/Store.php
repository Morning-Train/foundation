<?php

namespace morningtrain\Crud\Components;

use Illuminate\Config\Repository;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use morningtrain\Crud\Contracts\Model;

class Store {

    /**
     * @var string
     */
    protected $model;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $options;

    function __construct( $model, array $options = [] ) {
        $this->model = $model;
        $this->request = request();
        $this->options = new Repository($options);
    }

    /**
     * @param null $id
     * @return Model
     */
    public function one( $id = null, callable $callback = null ) {
        $model = $this->model;
        $resource = is_null($id) ? new $model() : $model::where('id', $id)->first();

        return !is_null($resource) && !is_null($callback) ? $callback($resource) : $resource;
    }

    /**
     * @return Collection
     */
    public function all( callable $callback = null ) {
        $query = $this->query();
        $appliedFilters = $this->applyFilters($query);
        $resources = $this->applyPagination($query);

        return is_null($callback) ? $resources : $callback($resources, $appliedFilters);
    }

    /**
     * @return Builder
     */
    public function query() {
        return (new $this->model)->newQuery();
    }

    /**
     * @return mixed
     */
    public function getSingularName() {
        return $this->one()->getSingularName();
    }

    /**
     * @return mixed|string
     */
    public function getPluralName() {
        return $this->one()->getPluralName();
    }

    /*
     * Filtering
     */

    /**
     * Adds a new filter
     *
     * @param string $name
     * @param callable $callback
     * @return $this
     */
    public function addFilter( string $name, callable $callback ) {
        $this->options->set("filters.$name", $callback);
        return $this;
    }

    /**
     * @param $query
     * @return mixed
     */
    protected function applyFilters( $query ) {
        $filters = $this->request->all();

        foreach($filters as $name => $value) {
            $filter = $this->options->get("filters.$name");

            if (is_callable($filter)) {
                $filter($query, $value);
            }
        }

        return $query;
    }

    /*
     * Pagination
     */

    protected function applyPagination( $query ) {
        return $query->paginate($this->options->get('pagination.limit', 10));
    }

}