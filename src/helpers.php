<?php

if (! function_exists('variable')) {
    /**
     * @param $name
     * @param null $default
     * @param null $group
     * @return mixed
     */
    function variable($name, $default = null, $group = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)
            ->setGroup($group)
            ->get($name, $default);
    }
}