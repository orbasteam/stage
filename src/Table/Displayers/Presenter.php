<?php

namespace Orbas\Stage\Table\Displayers;

use Illuminate\Database\Eloquent\Model;

class Presenter implements Displayable
{
    public function show($column, Model $item, $config)
    {
        return $item->present()->$column;
    }
}