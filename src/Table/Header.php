<?php

namespace Orbas\Stage\Table;

use Illuminate\Support\Collection;
use Orbas\Stage\AppException;
use Orbas\Stage\Table\Header\Item;

class Header extends Element
{
    /**
     * @var Collection|null
     */
    protected $items = null;
    
    /**
     * @return Collection
     * @throws AppException
     */
    public function items()
    {
        if ($this->items === null) {
            
            $result = new Collection();
            foreach ($this->getListConfig() as $data) {

                $column = $data['column'] ?? null;
                $name = isset($data['name']) ? $data['name'] : $this->getColumn($column, 'name');

                $result[] = new Item(array_merge($data, ['name' => $name]));
            }

            $this->items = $result;
        }
        
        return $this->items;
    }

}