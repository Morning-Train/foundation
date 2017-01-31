<?php

namespace morningtrain\Crud\Contracts;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{

    /**
     * @return string
     */
    public function getClass()
    {
        return get_called_class();
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        $reflect = new \ReflectionClass($this);

        return strtolower($reflect->getShortName());
    }

    /**
     * @return string
     */
    public function getPluralName()
    {
        return str_plural($this->getShortName());
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return !isset($this->id);
    }

    /*
     * WasNew helper
     */

    protected $was_new = false;

    /**
     * @return bool
     */

    public function wasNew()
    {
        return $this->isNew() || $this->was_new;
    }

    /**
     * Bind to save to overwrite was_new
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->was_new = $this->isNew();

        return parent::save($options);
    }

}