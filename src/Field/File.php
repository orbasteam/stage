<?php

namespace Orbas\Stage\Field;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use File as Local;

class File implements Storage
{
    /**
     * Get field config by table name
     *
     * @param string $table
     *
     * @return \Illuminate\Support\Collection|mixed
     */
    public function get($table)
    {
        $key  = implode('.', array_slice(func_get_args(), 1));
        $path = $this->path($table);
        
        if (!Local::exists($path)) {
            Local::put($path, $this->emptyStub());
        }

        $config = Local::getRequire($path);

        if ($key) {
            $value = array_get($config, $key);
            return is_array($value) ? new Collection($value) : $value;
        }

        return new Collection($config);
    }

    /**
     * @param string $table
     * @param mixed  $data
     *
     * @return $this
     */
    public function put($table, $data)
    {
        $path = $this->path($table);

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $data = var_export($data, true);
        $config = <<<FILE
<?php
return $data;
FILE;

        Local::put($path, $config);
        
        return $this;
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected function path($table)
    {
        $path = config('stage.global.field.path') . '/' . $table . '.php';
        return app()->basePath($path);
    }

    /**
     * get empty file stub
     * 
     * @return string
     */
    protected function emptyStub()
    {
        return <<<STUB
<?php
return [
    'columns' => [
    ]
];
STUB;
    }
}