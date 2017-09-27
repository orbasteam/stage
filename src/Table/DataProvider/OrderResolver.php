<?php

namespace Orbas\Stage\Table\DataProvider;

use Orbas\Stage\Table;

class OrderResolver
{
    /**
     * Resolve current order column and direction
     * 
     * @param Table $table
     *
     * @return array
     */
    public static function resolve(Table $table)
    {
        $column = static::currentOrderColumn($table);
        
        if ($column) {
            $direction = static::orderDirection();
            return [$column, $direction];
        }
        
        return [];
    }

    /**
     * get current order column
     * and this column is allowed to be order
     *
     * @param Table $table
     *
     * @return string
     */
    protected static function currentOrderColumn(Table $table)
    {
        $column = request()->get(config('stage.table.orderName'));
        if (!$column) {
            return null;
        }

        $listConfig = collect($table->getConfig('list.' . $table->getGroup()));
        $config = $listConfig->where('column', $column)->first();

        return $config && !empty($config['order']) ? $column : null;
    }

    /**
     * get order direction
     * 
     * @return string
     */
    protected static function orderDirection()
    {
        return request()->get(config('stage.table.orderByName')) ? 'asc' : 'desc';
    }
}