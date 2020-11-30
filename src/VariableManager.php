<?php

namespace Fomvasss\Variable;

use Illuminate\Database\Eloquent\Model;
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
    protected $langcode = null;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $variables = null;

    /** @var bool */
    protected $useCache;

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

        $this->variableModel = $variableModel;

        $this->cacheRepository = $cacheRepository;
    }

    /**
     * @param string|null $langcode
     * @param bool|null $useCache
     * @return \Illuminate\Database\Eloquent\Collection|mixed
     */
    public function all(?string $langcode = null, ?bool $useCache = null)
    {
        $langcode = $langcode ?: $this->langcode;
        $useCache = $useCache === null
            ? $this->useCache
            : $useCache;

        if ($langcode) {
            return $this->getCollection($useCache)->where('langcode', $langcode);
        }

        return $this->getCollection($useCache);
    }

    /**
     * @param string $key
     * @param null $default
     * @param string|null $langcode
     * @param bool|null $useCache
     * @return mixed|null
     */
    public function get(string $key, $default = null, ?string $langcode = null, ?bool $useCache = null)
    {
        $langcode = $langcode ?: $this->langcode;
        $useCache = $useCache === null
            ? $this->useCache
            : $useCache;

        if ($var = $this->getCollection($useCache)
            ->where('key', $key)
            ->where('langcode', $langcode)
            ->first()) {

            return $var->value ?: $default;
        }

        if ($var = $var = $this->getCollection($useCache)
            ->where('key', $key)
            ->where('langcode', null)
            ->first()) {

            return $var->value ?: $default;
        }
        
        return $default;
    }

    /**
     * @param string $key
     * @param null $value
     * @param string|null $langcode
     * @return mixed
     */
    public function save(string $key, $value = null, ?string $langcode = null)
    {
        return $this->getVariableModel()->updateOrCreate([
            'key' => $key,
            'langcode' => $langcode ?: $this->langcode,
        ], [
            'value' => $value,
        ]);
    }

    /**
     * @param string $key
     * @param array $default
     * @param string|null $langcode
     * @param bool|null $useCache
     * @return array|mixed
     */
    public function getArray(string $key, $default = [], ?string $langcode = null, ?bool $useCache = null)
    {
        $res = json_decode($this->get($key, '[]', $langcode, $useCache), true);

        return  empty($res) ? $default : $res;
    }

    /**
     * @param string $key
     * @param array $value
     * @param string|null $langcode
     * @return mixed
     */
    public function saveArray(string $key, $value = [], ?string $langcode = null)
    {
        $value = json_encode($value);

        return $this->save($key, $value, $langcode);
    }

    /**
     * @param string|null $langcode
     * @return $this
     */
    public function setLang(?string $langcode = null): VariableManagerContract
    {
        $this->langcode = $langcode;

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
