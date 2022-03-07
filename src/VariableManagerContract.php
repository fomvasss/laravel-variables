<?php

namespace Fomvasss\Variable;

interface VariableManagerContract
{
    /**
     * @param string|null $group
     * @param bool|null $useCache
     * @return \Illuminate\Database\Eloquent\Collection|mixed
     */
    public function all(?string $group = null, ?bool $useCache = null);

    /**
     * @param string $key
     * @param null $default
     * @param string|null $group
     * @param bool|null $useCache
     * @return mixed
     */
    public function get(string $key, $default = null, ?string $group = null, ?bool $useCache = null);

    /**
     * @param string $key
     * @param null $value
     * @param string|null $group
     * @return mixed
     */
    public function save(string $key, $value = null, ?string $group = null);

    /**
     * @param string $locale
     * @return mixed
     */
    public function setGroup(?string $group = null): VariableManagerContract;

    /**
     * @param bool $val
     * @return mixed
     */
    public function useCache(bool $val = true): VariableManagerContract;

    /**
     * @return bool
     */
    public function cacheClear();
}
