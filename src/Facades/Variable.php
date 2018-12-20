<?php

namespace Fomvasss\Variable\Facades;

use Fomvasss\Variable\VariableManagerContract;

class Variable extends \Illuminate\Support\Facades\Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return VariableManagerContract::class;
    }
}
