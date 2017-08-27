<?php

namespace Orbas\Stage\Table;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class Body extends Element
{
    /**
     * @return Collection
     */
    protected function getHeaderColumns()
    {
        return $this->table->getHeader()->pluck('column');
    }

    /**
     * @return EloquentCollection
     */
    protected function getData()
    {
        $config = [
            'columns' => $this->getColumns(),
            'load'    => $this->getEagerLoadTable()
        ];
        $dataProvider = new DataProvider($this->table, $config);
        
        return $dataProvider->getData();
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

        foreach ($this->getData() as $item) {
            $row = [];

            foreach ($header as $config) {
                $column = $config['column'];
                $row[$column] = Displayer::output($column, $item, $this->getListColumn($column));
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