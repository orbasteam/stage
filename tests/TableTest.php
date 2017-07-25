<?php

namespace Tests;

use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Orbas\Stage\Field\Storage;
use Tests\Stubs\User;
use Tests\Stubs\UserController;

class TableTest extends StageTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->initRoute();
    }

    protected function initRoute()
    {
        $route = $this->initMock(Route::class);
        $route->shouldReceive('getController')
              ->once()
              ->andReturn(new UserController);

        request()->setRouteResolver(function () use ($route) {
            return $route;
        });
    }

    /**
     * @test
     * @group Table
     */
    public function it_should_auto_parse_model_name()
    {
        $this->assertInstanceOf(User::class, app('table')->getModel());
    }

    /**
     * @test
     * @group Table
     */
    public function it_should_get_users_as_table_name()
    {
        $this->assertEquals('users', app('table')->getTableName());
    }

    /**
     * @test
     * @group Table
     */
    public function it_should_get_collection_of_headers()
    {
        $this->mockStorage();
        $this->assertInstanceOf(Collection::class, app('table')->getHeader());
    }

    /**
     */
    public function it_should_get_collection_of_body()
    {
//        $this->assertInstanceOf(Collection::class, app('table')->getBody());
    }

    protected function mockStorage()
    {
        $fields = require __DIR__ . '/fields/users.php';
        $storage = $this->initMock(Storage::class, 'stage.storage');
        $storage->shouldReceive('get')
                ->once()
                ->withArgs(['users'])
                ->andReturn(new Collection($fields));
    }
}