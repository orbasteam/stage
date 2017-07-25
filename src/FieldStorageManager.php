<?php

namespace Orbas\Stage;

use Illuminate\Support\Manager;
use Orbas\Stage\Field\File;

class FieldStorageManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->createFileDriver();
    }

    protected function createFileDriver()
    {
        return new File();
    }
}