<?php

namespace Orbas\Stage\Http\Controllers;

use File;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Orbas\Stage\Http\Services\ColumnService;

class StageController extends Controller
{
    /**
     * @param ColumnService $service
     *
     * @return string
     */
    public function index(ColumnService $service)
    {
        return view('stage::index')->with(
            [
                'tables' => $service->getTables(),
                'enums' => $this->getEnums()
            ]
        );
    }

    /**
     * get enums name
     *
     * @return Collection
     */
    protected function getEnums()
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
        
        $collection = collect(File::allFiles($path));
        return $collection->map(function($file) {
            preg_match('/(\w+)\.php/', $file->getFilename(), $matches);
            return $matches[1] ?? null;
        });
    }
}