<?php

namespace Orbas\Stage\Table;

use Illuminate\Support\Collection;
use Orbas\Stage\AppException;

class Header extends Element
{
    /**
     * @return Collection
     * @throws AppException
     */
    public function items()
    {
        $result = new Collection();
        foreach ($this->getListConfig() as $data) {

            $column = $data['column'];
            $name   = isset($data['name']) ? $data['name'] : $this->getColumn($column, 'name');
            
            $result[] = [
                'column' => $column,
                'name'   => $name
            ];
        }
        
        return $result;
    }
}