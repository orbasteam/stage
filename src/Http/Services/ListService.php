<?php

namespace Orbas\Stage\Http\Services;

use Illuminate\Support\Collection;

class ListService extends Service
{
    /**
     * @param string $table
     * @param string $group
     * @param string $column
     * @param array  $data
     */
    public function put($table, $group, $column, $data)
    {
        if (empty($data['column'])) {
            $this->removeColumn($table, $group, $column);
            return;
        }
        
        if ($column) {
            $this->updateColumn($table, $group, $column, $data);
        } else {
            $this->addColumn($table, $group, $data);
        }
    }

    /**
     * @param string $table
     * @param string $group
     * @param string $column
     * @param array  $data
     */
    protected function updateColumn($table, $group, $column, $data)
    {
        $this->updateData($table, function($config) use ($group, $column, $data) {
            $lisGroup  = Collection::make($config['list'][$group]);
            $groupData = $lisGroup->map(function($item) use ($column, $data) {

                if ($item['column'] == $column) {
                    return $data;
                }

                return $item;
            });

            $config = $config->toarray();
            array_set($config, "list.$group", $groupData->values()->toArray());

            return $config;
        });
    }

    protected function addColumn($table, $group, $data)
    {
        $this->updateData($table, function($config) use ($group, $data) {

            $config = $config->toArray();
            $config['list'][$group][] = $data;

            return $config;
        });
    }

    /**
     * @param string $table
     * @param string $group
     * @param string $column
     */
    public function removeColumn($table, $group, $column)
    {
        $this->updateData($table, function($config) use ($group, $column) {

            $list = Collection::make($config['list'][$group]);
            $key  = $list->search(function($item) use ($column) {
                return $item['column'] == $column;
            });

            $data = $list->reject(function ($value, $k) use ($key) {
                return $k == $key;
            })->values()->toArray();

            $config = $config->toArray();
            $config['list'][$group] = $data;

            return $config;
        });
    }

    /**
     * @param string $table
     * @param string $group
     */
    public function createGroup($table, $group)
    {
        $config = $this->storage()->get($table)->toArray();
        $config['list'][$group] = [];
        $this->storage()->put($table, $config);
    }

    /**
     * @param string $table
     * @param string $group
     */
    public function removeGroup($table, $group)
    {
        $this->updateData($table, function($config) use ($group) {
            return $config->except("list.$group");
        });
    }
}