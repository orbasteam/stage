<?php

namespace Orbas\Stage\Http\Controllers;

use Illuminate\Routing\Controller;
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
        return view('stage::index')->with('tables', $service->getTables());
    }
}