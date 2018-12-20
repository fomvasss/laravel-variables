<?php

namespace Fomvasss\Variable;

interface VariableManagerContract
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param string $locale
     * @return mixed
     */
    public function locale(string $locale = null);
}
