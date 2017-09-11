<?php

namespace Orbas\Stage\Http\Controllers;

use Illuminate\Routing\Controller;
use Orbas\Stage\Http\Services\ColumnService;
use Orbas\Stage\Http\Services\StageService;

class StageController extends Controller
{
    /**
     * @param ColumnService $service
     * @param StageService  $stageService
     *
     * @return string
     */
    public function index(ColumnService $service, StageService $stageService)
    {
        return view('stage::index')->with(
            [
                'tables' => $service->getTables(),
                'enums' => $stageService->getEnums(),
                'tableDefaultOptions' => config('stage.table')
            ]
        );
    }
}