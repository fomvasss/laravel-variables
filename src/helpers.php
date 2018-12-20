<?php

if (! function_exists('variable')) {
    /**
     * @param $name
     * @param null $default
     * @param null $locale
     * @return mixed
     */
    function variable($name = null, $default = null, $locale = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)
            ->locale($locale)
            ->get($name, $default);
    }
}