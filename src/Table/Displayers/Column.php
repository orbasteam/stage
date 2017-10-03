<?php

namespace Orbas\Stage\Table\Displayers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Orbas\Stage\Table\Displayer;

class Column implements Displayable
{
    public function show($column, Model $item, $config)
    {
        list($column, $attribute) = Displayer::parseColumnName($column);

        if ($attribute) {
            $item = $item->$column;
            $column = $attribute;
        }

        return $item ? call_user_func_array([$item, 'getAttribute'], [$column]) : '';
    }
}