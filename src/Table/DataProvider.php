<?php

namespace Orbas\Stage\Table;

use Closure;
use Illuminate\Config\Repository;
use Orbas\Stage\Table;
use Orbas\Stage\Table\DataProvider\OrderResolver;

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
     * @var Closure
     */
    protected static $orderResolver;

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
     * @return mixed
     */
    public function getPaginator()
    {
        $model = $this->table->getModel()->newQuery();
        
        list($order, $direction) = $this->resolveOrder();
        if ($order) {
            $model->orderBy($order, $direction);
        }
        
        $filter = $this->table->getFilter();
        if (is_callable($filter)) {
            $filter($model);
        }

        if ($this->table->enablePaginate()) {
            $collection = $model->paginate($this->config->get('options.rowPerPage'), $this->config->get('columns'));
        } else {
            $collection = $model->get($this->config->get('columns'));
        }
        
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

    /**
     * @param Closure $resolver
     * 
     */
    public static function orderResolver(Closure $resolver)
    {
        static::$orderResolver = $resolver;
    }

    /**
     * @return array
     */
    protected function resolveOrder()
    {
        if (is_callable(static::$orderResolver)) {
            return call_user_func(static::$orderResolver, $this->table);
        }
        
        return OrderResolver::resolve($this->table);
    }
}