<?php
namespace Orbas\Stage\Http\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Orbas\Stage\Http\Repository\Database;

class ColumnService extends Service
{
    /**
     * @var Database
     */
    private $database;

    /**
     * ColumnService constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    
    /**
     * get all table name
     *
     */
    public function getTables()
    {
        return $this->database->getTables();
    }

    /**
     * @param string $table
     * @param array  $data
     */
    public function put($table, $data)
    {
        $this->updateData($table, function($config) use ($data) {
            $config = $config->toArray();
            Arr::set($config, "columns", $data);
            return $config;
        });
    }

    /**
     * @param $table
     *
     * @return Collection
     */
    public function getConfig($table)
    {
        $columnsInConfig = $this->storage()->get($table, 'columns');
        $config = $this->storage()->get($table);

        return tap($config, function($collection) use ($columnsInConfig, $table) {
            $collection['columns'] = $this->columnInDb($table)->merge($columnsInConfig)->toArray();
        });
    }

    /**
     * @param string $table
     *
     * @return Collection
     */
    protected function columnInDb($table)
    {
        $columns = $this->database->getColumns($table);
        return (new Collection($columns))->mapWithKeys(function ($item, $key) {

            $type = $item->Type;
            $length = null;
            if (preg_match('/^(\w+)\((\d+)\)/', $item->Type, $matches)) {
                $type = $matches[1];
                $length = $matches[2];
            }

            return [
                $item->Field => [
                    'type'   => $type,
                    'length' => $length
                ]
            ];
        });
    }

}