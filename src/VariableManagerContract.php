<?php

namespace Fomvasss\Variable;

interface VariableManagerContract
{
    public function all(): array;

    public function get($name, $default = null);

    public function set($name, $value = null): int;

    public function delete($name): int;

    public function locale(string $locale = null);

    public function setArray(array $attributes): int;
}
