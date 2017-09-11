<?php

namespace Orbas\Stage\Table;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Body extends Element
{
    /**
     * @var LengthAwarePaginator
     */
    protected $data;
    
    /**
     * @return Collection
     */
    protected function getHeaderColumns()
    {
        return $this->table->getHeader()->pluck('column');
    }

    /**
     * @return mixed
     */
    protected function getListOptions()
    {
        $group = $this->table->getGroup();
        return $this->table->getConfig("listOptions.$group");
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginator()
    {
        if (!$this->data) {
            
            $config = [
                'columns' => $this->getColumns(),
                'load'    => $this->getEagerLoadTable(),
                'options' => $this->getListOptions()
            ];
            
            $this->data = (new DataProvider($this->table, $config))->getPaginator();
        }
        
        return $this->data;
    }

    /**
     * retrieve items
     * 
     * @return Collection
     */
    public function items()
    {
        $result = new Collection();
        $header = $this->table->getHeader();
        $listOptions = $this->getListOptions();

        foreach ($this->getPaginator() as $item) {
            
            $row = [];
            
            foreach ($header as $config) {
                $column = $config['column'] ?? null;
                $row[] = Displayer::output($column, $item, [
                    'options' => $listOptions,
                    'config'  => $this->getListColumnByToken($config['token'])
                ]);
            }
            
            $result->push($row);
        }
        
        return $result;
    }

    /**
     * filter columns to avoid selecting non-existent columns in table  
     * 
     * @param array $columns
     *
     * @return void
     */
    protected function filterColumn(array &$columns)
    {
        $tableColumns = \Schema::getColumnListing($this->getModel()->getTable());
        $columns = array_intersect($tableColumns, $columns);
    }

    /**
     * @param array $columns
     *
     * @return void
     */
    protected function pushForeignKey(array &$columns)
    {
        $entity = $this->getModel();
        
        $this->parseColumns(function($column, $attribute) use ($entity, &$columns) {
            if ($attribute && $this->isBelongsTo($column)) {
                array_push($columns, $entity->$column()->getForeignKey());
            }
        });
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    protected function isBelongsTo($method)
    {
        $model = $this->getModel();
        return method_exists($model, $method) && $model->$method() instanceof BelongsTo;
    }

    /**
     * get columns which table need
     * 
     * @return array
     */
    protected function getColumns()
    {
        $columns = $this->getHeaderColumns()->toArray();
        $this->pushForeignKey($columns);
        $this->filterColumn($columns);
        
        // add primary key
        $columns[] = $this->getModel()->getKeyName();
        
        return array_values($columns);
    }

    /**
     * get eager load tables prevent n+1 query issue
     * 
     * @return array
     */
    protected function getEagerLoadTable()
    {
        return $this->parseColumns(function($column, $attribute) {
            return $attribute && $this->isBelongsTo($column) ? $column : null;
        });
    }

    /**
     * @param \Closure $closure
     *
     * @return array
     */
    protected function parseColumns(\Closure $closure)
    {
        $result = [];
        foreach ($this->getHeaderColumns() as $column) {
            list($column, $attribute) = Displayer::parseColumnName($column);
            
            $value = $closure($column, $attribute);
            if ($value !== null) {
                $result[] = $value;
            }
        }
        
        return $result;
    }
}