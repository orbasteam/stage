<?php

namespace Orbas\Stage\Table\Displayers;

use Illuminate\Database\Eloquent\Model;

interface Displayable
{
    /**
     * @param string $column
     * @param Model  $item
     * @param array  $config
     *
     * @return mixed
     */
    public function show($column, Model $item, $config); 
}