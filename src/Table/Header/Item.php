<?php

namespace Orbas\Stage\Table\Header;

use Illuminate\Config\Repository;

class Item extends Repository
{
    /**
     * @var false|string
     */
    protected static $orderColumn = false;

    /**
     * @var false|string
     */
    protected $currentOrder = false;
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $data = [
            'item' => $this,
            'url' => $this->orderUrl()
        ];

        return view('table::header-item', $data);
    }

    public function orderUrl()
    {
        $orderParam = config('stage.table.orderName');
        $orderByParam = config('stage.table.orderByName');
        $asc = static::orderAsc() ? 0 : 1;

        return request()->fullUrlWithQuery([
            $orderParam => $this->get('column'), 
            $orderByParam => $asc
        ]);
    }
    
    /**
     * @return string
     */
    public function currentOrder()
    {
        if (!$this->currentOrder) {

            $this->currentOrder = '';
            
            if (static::orderColumn() == $this->get('column')) {
                $this->currentOrder = static::orderAsc() ? 'asc' : 'desc';
            }
        }
        
        return $this->currentOrder;
    }

    /**
     * @return string|null
     */
    protected static function orderAsc()
    {
        return request()->get(config('stage.table.orderByName'));
    }

    /**
     * @return string|null
     */
    protected static function orderColumn()
    {
        if (static::$orderColumn === false) {
            static::$orderColumn = request()->get(config('stage.table.orderName'));
        }
        
        return static::$orderColumn;
    }
}