<?php

namespace Fomvasss\Variable;

interface VariableManagerContract
{
    /**
     * @param string|null $langcode
     * @param bool|null $useCache
     * @return \Illuminate\Database\Eloquent\Collection|mixed
     */
    public function all(?string $langcode = null, ?bool $useCache = null);

    /**
     * @param string $key
     * @param null $default
     * @param string|null $langcode
     * @param bool|null $useCache
     * @return mixed
     */
    public function get(string $key, $default = null, ?string $langcode = null, ?bool $useCache = null);

    /**
     * @param string $key
     * @param null $value
     * @param string|null $langcode
     * @return mixed
     */
    public function save(string $key, $value = null, ?string $langcode = null);

    /**
     * @param string $locale
     * @return mixed
     */
    public function setLang(?string $langcode = null): VariableManagerContract;

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
