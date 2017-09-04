<?php

namespace Orbas\Stage\Http\Services;

class ListService extends Service
{
    const COLUMN = 0;
    const PRESENTER = 1;
    const ENUM = 2;
    
    /**
     * @param string $table
     * @param string $group
     * @param array  $data
     */
    public function put($table, $group, $data)
    {
        $this->updateData($table, function($config) use ($group, $data) {
            $config = $config->toArray();
            
            if (isset($data['data'])) {
                array_set($config, "list.$group", $data['data']);
            }
            
            if (isset($data['option'])) {
                array_set($config, "listOptions.$group", $data['option']);
            }
            
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