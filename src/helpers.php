<?php

if (! function_exists('variable')) {
    /**
     * @param $name
     * @param null $default
     * @param null $locale
     * @return mixed
     */
    function variable($name, $default = null, $langcode = null)
    {
        return app(\Fomvasss\Variable\VariableManagerContract::class)
            ->setLang($langcode)
            ->get($name, $default);
    }
}