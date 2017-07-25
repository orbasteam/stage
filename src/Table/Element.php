<?php
namespace Orbas\Stage\Table;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Orbas\Stage\AppException;
use Orbas\Stage\Table;

abstract class Element
{
    /**
     * @var array
     */
    protected $columns = false;
    /**
     * @var Table
     */
    protected $table;

    /**
     * Element constructor.
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    protected function getGroup()
    {
        return $this->table->getGroup();
    }

    /**
     *
     * @param array $key
     *
     * @return mixed
     */
    protected function getColumn(...$key)
    {
        return $this->getConfig('columns', $key);
    }

    /**
     * @param array $key
     *
     * @return mixed
     * @throws AppException
     */
    protected function getListConfig(...$key)
    {
        return $this->getConfig('list', $this->getGroup());
    }

    /**
     * @param string $column
     * @param array  $key
     *
     * @return mixed
     * @throws AppException
     */
    protected function getListColumn($column, ...$key)
    {
        $data = $this->getListConfig();
        $search = collect($data)->search(function($item) use ($column) {
            return $item['column'] == $column;
        });

        if ($search === false) {
            throw new AppException("list config can't find column: $column");
        }
        
        return $this->getConfig('list', $this->getGroup(), $search, $key);
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        $key = implode('.', array_flatten(func_get_args()));
        return $this->table->getConfig($key);
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->table->getModel();
    }
}