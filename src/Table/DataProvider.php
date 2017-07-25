<?php

namespace Orbas\Stage\Table;

use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DataProvider
{
    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Model
     */
    private $model;

    /**
     * DataProvider constructor.
     *
     * @param Model $model
     * @param array $config
     */
    public function __construct(Model $model, $config)
    {
        $this->config = new Repository($config);
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function getData()
    {
        $collection = $this->model::paginate(null, $this->config->get('columns'));
        $this->eagerLoad($collection);
        
        return $collection;
    }

    /**
     * @param $collection
     */
    protected function eagerLoad($collection)
    {
        if (empty($this->config['load'])) {
            return;
        }
        
        foreach ($this->config['load'] as $table) {
            $collection->load($table);
        }
    }
}