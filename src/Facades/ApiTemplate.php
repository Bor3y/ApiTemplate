<?php

namespace Bor3y\ApiTemplate\Facades;

use Illuminate\Support\Facades\Facade;

class ApiTemplate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'apitemplate';
    }
}
