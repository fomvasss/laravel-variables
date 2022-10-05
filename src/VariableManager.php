<?php

namespace Fomvasss\Variable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class VariableManager
 *
 * @package \Fomvasss\Variable
 */
class VariableManager implements VariableManagerContract
{
    protected $app;

    /** @var \Fomvasss\Variable\Models\Variable */
    protected $variableModel;

    /** @var \Illuminate\Cache\Repository */
    protected $cacheRepository;

    /** @var string */
    protected $group = null;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $variables = null;

    /** @var bool */
    protected $useCache;

    /** @var mixed|string */
    protected $arrayDelimiter;
    
    /** bool @var */
    protected $fallbackAny;

    /**
     * VariableManager constructor.
     * @param $variableModel
     * @param $cacheRepository
     * @param null $app
     */
    public function __construct($variableModel, $cacheRepository, $app = null)
    {
        if (! $app) {
            $app = app();   //Fallback when $app is not given
        }
        $this->app = $app;

        $this->config = $this->app['config']->get('variables');
        
        $this->useCache = $this->config['cache']['is_use_cache'] ?? true;

        $this->arrayDelimiter = $this->config['array_delimiter'] ?? '';
        
        $this->fallbackAny = $this->config['fallback_any'] ?? false;

        $this->variableModel = $variableModel;

        $this->cacheRepository = $cacheRepository;
    }

    /**
     * @param string|null $group
     * @param bool|null $useCache
     * @return \Illuminate\Database\Eloquent\Collection|mixed
     */
    public function all(?string $group = null, ?bool $useCache = null)
    {
        $group = $group ?: $this->group;
        $useCache = $useCache === null
            ? $this->useCache
            : $useCache;

        if ($group) {
            return $this->getCollection($useCache)->where('group', $group);
        }

        return $this->getCollection($useCache);
    }

    /**
     * @param string $key
     * @param null $default
     * @param string|null $group
     * @param bool|null $useCache
     * @return mixed|null
     */
    public function get(string $key, $default = null, ?string $group = null, ?bool $useCache = null)
    {
        $group = $group ?: $this->group;
        $useCache = $useCache === null
            ? $this->useCache
            : $useCache;

        if ($var = $this->getCollection($useCache)
            ->where('key', $key)
            ->where('group', $group)
            ->first()) {

            if (!is_null($var->value ?? $default)) {
                return $var->value ?? $default;
            }
        }

        if ($var = $this->getCollection($useCache)
            ->where('key', $key)
            ->where('group', null)
            ->first()) {

            return $var->value ?? $default;
        }


        if ($this->fallbackAny && ($var = $var = $this->getCollection($useCache)
            ->where('key', $key)
            ->whereNotNull('group')
            ->first())) {

            return $var->value ?? $default;
        }
        
        return $default;
    }

    /**
     * @param string $key
     * @param null $value
     * @param string|null $group
     * @return mixed
     */
    public function save(string $key, $value = null, ?string $group = null)
    {
        return $this->getVariableModel()->updateOrCreate([
            'key' => $key,
            'group' => $group ?: $this->group,
        ], [
            'value' => $value,
        ]);
    }

    /**
     * @param string $key
     * @param array $default
     * @param string|null $group
     * @param bool|null $useCache
     * @return array|mixed
     */
    public function getArray(string $key, $default = [], ?string $group = null, ?bool $useCache = null)
    {
        $varKey = $key;
        $varKeys = '';
        if ($d = $this->arrayDelimiter) {
            $varKey = substr($key, 0, strpos($key, $d)) ?: $key;
            $varKeys = strpos($key, $d) ? substr($key,  strpos($key, $d) + 1) : '';
        }
        
        $res = json_decode($this->get($varKey, '[]', $group, $useCache), true);

        if ($varKeys) {
            $res = Arr::get($res, $varKeys);
        }

        return empty($res) ? $default : $res;
    }

    /**
     * @param string $key
     * @param array $value
     * @param string|null $group
     * @return mixed
     */
    public function saveArray(string $key, $value = [], ?string $group = null)
    {
        $value = json_encode($value);

        return $this->save($key, $value, $group);
    }

    /**
     * @param string|null $group
     * @return $this
     */
    public function setGroup(?string $group = null): VariableManagerContract
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @param bool $val
     * @return $this|mixed
     */
    public function useCache(bool $val = true): VariableManagerContract
    {
        $this->useCache = $val;
        
        return $this;
    }

    /**
     * @return bool
     */
    public function cacheClear()
    {
        return $this->cacheRepository->forget($this->config['cache']['name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getCollection(bool $useCache): \Illuminate\Database\Eloquent\Collection
    {
        try {
            if ($useCache) {
                return $this->cacheRepository->remember(
                    $this->config['cache']['name'],
                    $this->config['cache']['time'],
                    function () {
                        return $this->getVariableModel()->all();
                    });  
            } else {
                return $this->getVariableModel()->all();
            }
        } catch (\Exception $exception) {
            $this->app['log']->warning($exception->getMessage());

            return new \Illuminate\Database\Eloquent\Collection;
        }
    }

    /**
     * @return Model
     */
    protected function getVariableModel(): Model
    {
        return $this->variableModel;
    }
}
