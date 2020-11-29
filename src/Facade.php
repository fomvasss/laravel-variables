<?php

namespace Fomvasss\Variable;

/**
 * @see \Fomvasss\Variable\VariableManager
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return VariableManagerContract::class;
    }
}
