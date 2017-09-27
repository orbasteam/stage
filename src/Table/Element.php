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
    public function getListConfig(...$key)
    {
        return $this->getConfig('list', $this->getGroup());
    }

    /**
     * @param string $token
     * @param array  $key
     *
     * @return mixed
     * @throws AppException
     */
    protected function getListColumnByToken($token, ...$key)
    {
        $data = $this->getListConfig();
        $search = collect($data)->search(function($item) use ($token) {
            return $item['token'] == $token;
        });
        
        if ($search === false) {
            return [];
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