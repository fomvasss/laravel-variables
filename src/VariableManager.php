<?php

namespace Fomvasss\Variable;

use Illuminate\Cache\Repository as CacheRepository;

/**
 * Class VariableManager
 *
 * @package \Fomvasss\Variable
 */
class VariableManager implements VariableManagerContract
{
    protected $variableModel;

    protected $cacheRepo;

    protected $locale = null;

    private $cacheName = 'fomvasss.variables.cache';

    protected $cacheTime;

    /**
     * VariableManager constructor.
     *
     * @param \Illuminate\Cache\Repository $cacheRepo
     */
    public function __construct(CacheRepository $cacheRepo)
    {
        $this->cacheTime = config('variables.cache.time', 360);
        $this->variableModel = $this->model();
        $this->cacheRepo = $cacheRepo;
    }

    /**
     * @return mixed
     */
    protected function model()
    {
        return app()->make(config('variables.model', \Fomvasss\Variable\Variable::class));
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $settings = $this->getAll();
        return $settings->pluck('value', 'name')->toArray();
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function first($name, $default = null)
    {
        if (!empty($setting = $this->firstByName($name))) {
            return $setting->value;
        }

        return $default;
    }

    /**
     * @param $name
     * @param null $value
     * @param null $description
     * @return int
     */
    public function set($name, $value = null, $description = null): int
    {
        $this->cacheRepo->forget($this->cacheName);

        return $this->variableModel->updateOrCreate([
            'name' => $name, 'locale' => $this->locale
        ], [
            'value' => $value, 'description' => $description,
        ]) ? 1 : 0;
    }

    /**
     * @param $name
     * @return int
     */
    public function delete($name): int
    {
        $this->cacheRepo->forget($this->cacheName);
        $res = $this->variableModel->where('name', $name)->where('locale', $this->locale)->delete();

        return $res ? 0 : 1;
    }

    /**
     * @param string|null $locale
     * @return $this
     */
    public function locale(string $locale = null)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @param array $attributes
     * @return int
     */
    public function setArray(array $attributes): int
    {
        $r = 0;
        foreach ($attributes as $name => $value) {
            $this->variableModel->updateOrCreate([
                'name' => $name, 'locale' => $this->locale
            ], [
                'value' => $value
            ]);
            $r++;
        }
        $this->cacheRepo->forget($this->cacheName);
        return $r;
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function firstByName($name)
    {
        return $this->getAll()->where('name', $name)->where('locale', $this->locale)->first();
    }

    /**
     * @return mixed
     */
    protected function getAll()
    {
        $settings = $this->cacheRepo->remember($this->cacheName, $this->cacheTime, function () {
            return $this->variableModel->all();
        });

        return $this->locale ? $settings->where('locale', $this->locale) : $settings;
    }
}
