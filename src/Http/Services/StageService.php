<?php

namespace Orbas\Stage\Http\Services;

use File;
use Illuminate\Support\Collection;

class StageService
{
    /**
     * get enums name
     *
     * @return Collection
     */
    public function getEnums()
    {
        return $this->getFileNameFromPath(app_path('Enums'));
    }

    /**
     * @param string $path
     * @return Collection
     */
    protected function getFileNameFromPath($path)
    {
        if (!File::isDirectory($path)) {
            return collect([]);
        }

        $collection = collect(File::files($path));
        return $collection->map(function($file) {
            preg_match('/(\w+)\.php/', $file, $matches);
            return $matches[1] ?? null;
        });
    }
}