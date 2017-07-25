<?php
namespace Orbas\Stage\Http\Services;

use Orbas\Stage\Field\Storage;

abstract class Service
{
    /**
     * @param string   $table
     * @param callable $closure
     */
    protected function updateData($table, callable $closure)
    {
        $storage = $this->storage();
        $storage->put($table, $closure($storage->get($table)));
    }

    /**
     * @return Storage
     */
    protected function storage()
    {
        return app('stage.storage');
    }
}