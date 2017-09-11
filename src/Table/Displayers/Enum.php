<?php

namespace Orbas\Stage\Table\Displayers;

use Illuminate\Database\Eloquent\Model;

class Enum implements Displayable
{
    public function show($column, Model $item, $config)
    {
        $enum = $config['config']['enum'] ?? null;

        return $item->present()->enum($column, $enum);
    }
}