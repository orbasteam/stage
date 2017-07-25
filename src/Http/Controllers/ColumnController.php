<?php

namespace Orbas\Stage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Orbas\Stage\Http\Services\ColumnService;

class ColumnController extends Controller
{
    /**
     * @var ColumnService
     */
    private $service;

    /**
     * ColumnController constructor.
     *
     * @param ColumnService $service
     */
    public function __construct(ColumnService $service)
    {
        $this->service = $service;
    }
    
    /**
     * get columns
     *
     * @param string $table
     *
     * @return \Illuminate\Support\Collection
     */
    public function show($table)
    {
        return response()->json($this->service->getConfig($table));
    }

    /**
     * @param string  $table
     * @param Request $request
     */
    public function update($table, Request $request)
    {
        $this->service->put($table, $request->column, $request->data);
    }
}