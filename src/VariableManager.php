<?php

namespace Fomvasss\Variable;

/**
 * Class VariableManager
 *
 * @package \Fomvasss\Variable
 */
class VariableManager implements VariableManagerContract
{
    protected $app;

    protected $variableModel;

    protected $cacheRepository;

    protected $locale = null;

    protected $variables = null;

    /**
     * VariableManager constructor.
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

        $this->variableModel = $variableModel;

        $this->cacheRepository = $cacheRepository;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null, bool $useCache = true)
    {
        if ($useCache === false) {
            $var = $this->variableModel->where('key', $key)
                ->when($this->locale, function ($q) {
                    $q->where('locale', $this->locale);
                })
                ->first();
            return $var ? $var->value : $default;
        }

        if ($collection = $this->getCollection()) {
            if ($var = $collection
                ->where('key', $key)
                ->where('locale', $this->locale)
                ->first()) {

                return $var->value ?: $default;
            }

            if ($var = $collection
                ->where('key', $key)
                ->where('locale', null)
                ->first()) {

                return $var->value ?: $default;
            }
        }

        return $default;
    }

    public function set(string $key, $value = null, $locale = null)
    {
        return $this->variableModel->updateOrCreate([
            'key' => $key,
            'locale' => $locale ?: $this->locale,
        ], [
            'value' => $value,
        ]);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        if ($all = $this->getCollection()) {
            $allByLocale = $this->locale ? $all->where('locale', $this->locale) : $all;
            $this->variables = $allByLocale->toArray();
        } else {
            $this->variables = [];
        }

        return $this->variables;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function locale(string $locale = null)
    {
        $this->locale = $locale;

        return $this;
    }

    public function cacheOff()
    {

    }

    public function cacheClear()
    {
        return $this->cacheRepository->forget($this->config['cache']['name']);
    }

    /**
     * @return |null
     */
    protected function getCollection()
    {
        try {
            return $this->cacheRepository->remember($this->config['cache']['name'], $this->config['cache']['time'], function () {
                return $this->variableModel->select('key', 'value', 'locale')->get();
            });
        } catch (\Exception $exception) {
            $this->app['log']->info(__CLASS__ . ': ' . $exception->getMessage());

            return false;
        }
    }
}
