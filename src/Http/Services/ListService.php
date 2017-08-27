<?php

namespace Orbas\Stage\Http\Services;

use Illuminate\Support\Collection;

class ListService extends Service
{
    /**
     * @param string $table
     * @param string $group
     * @param array  $data
     */
    public function put($table, $group, $data)
    {
        $this->updateData($table, function($config) use ($group, $data) {
            
            $config = $config->toArray();
            array_set($config, "list.$group", $data);
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