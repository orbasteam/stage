<?php

namespace Orbas\Stage\Facades;

use Illuminate\Support\Facades\Facade;
use Orbas\Stage\Navigation as Nav;

class Navigation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Nav::class;
    }
}