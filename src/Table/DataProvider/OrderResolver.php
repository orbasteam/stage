<?php

namespace Orbas\Stage\Table\DataProvider;

use Orbas\Stage\Table;

class OrderResolver
{
    public static function resolve(Table $table)
    {
        $orderColumn = request()->get(config('stage.table.orderName'));
        if (!$orderColumn) {
            return [];
        }
        
        $listConfig = collect($table->getConfig('list.' . $table->getGroup()));
        $config = $listConfig->where('column', $orderColumn)->first();
        
        if (!$config || empty($config['order'])) {
            return [];
        }
        
        $direction = request()->get(config('stage.table.orderByName')) ? 'asc' : 'desc';
        return [$orderColumn, $direction];
    }
}