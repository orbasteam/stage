<?php

namespace Orbas\Stage\Table;

use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Collection;
use Orbas\Stage\Table;

class DataProvider
{
    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Table
     */
    protected $table;

    /**
     * DataProvider constructor.
     *
     * @param Table $table
     * @param array $config
     */
    public function __construct(Table $table, $config)
    {
        $this->config = new Repository($config);
        $this->table = $table;
    }

    /**
     * @return Collection
     */
    public function getData()
    {
        $model = $this->table->getModel()->newQuery();
        
        $filter = $this->table->getFilter();
        if (is_callable($filter)) {
            $filter($model);
        }

        $collection = $model->paginate(null, $this->config->get('columns'));
        return tap($collection, function($collection) {
            $this->eagerLoad($collection);
        });
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