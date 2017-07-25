<?php

namespace Orbas\Stage\Field;

interface Storage
{
    /**
     * Get field config by table name
     *
     * @param string $table
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($table);

    /**
     * @param string $table
     * @param mixed  $data
     *
     * @return $this
     */
    public function put($table, $data);
}