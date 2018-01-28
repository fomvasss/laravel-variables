<?php

if (! function_exists('var_all')) {
    function var_all($locale = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)->locale($locale)->all();
    }
}

if (! function_exists('var_get')) {
    function var_get($name, $default = null, $locale = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)->locale($locale)->first($name, $default);
    }
}

if (! function_exists('var_set')) {
    function var_set($name, $value = null, $locale = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)->locale($locale)->set($name, $value, $locale);
    }
}


if (! function_exists('var_delete')) {
    function var_delete($name, $locale = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)->locale($locale)->delete($name, $locale);
    }
}

if (! function_exists('var_set_array')) {
    function var_set_array(array $attributes, $locale = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)->locale($locale)->setArray($attributes);
    }
}
