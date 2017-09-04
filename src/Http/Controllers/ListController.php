<?php
namespace Orbas\Stage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Orbas\Stage\Http\Services\ListService;

class ListController extends Controller
{
    /**
     * @var ListService
     */
    private $service;

    /**
     * ListController constructor.
     *
     * @param ListService $service
     */
    public function __construct(ListService $service)
    {
        $this->service = $service;
    }
    
    /**
     * @param string  $table
     * @param string  $group
     * @param Request $request
     */
    public function update($table, $group, Request $request)
    {
        $this->service->put($table, $group, $request->data);
    }

    /**
     * @param string $table
     * @param string $group
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroyGroup($table, $group)
    {
        if ($group == 'default') {
            return response('default can\'t be destroyed', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->service->removeGroup($table, $group);
    }

    /**
     * @param string  $table
     * @param Request $request
     */
    public function createGroup($table, Request $request)
    {
        $this->service->createGroup($table, $request->group);
    }
}