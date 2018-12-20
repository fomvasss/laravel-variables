<?php

namespace Fomvasss\Variable;

/**
 * Class VariableManager
 *
 * @package \Fomvasss\Variable
 */
class VariableManager implements VariableManagerContract
{
    protected $variableModel;

    protected $cacheRepository;

    protected $locale;

    protected $variables;

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
     * @param string|null $default
     * @return mixed|string
     */
    public function get(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        if (! isset($this->variables)) {

            $all = $this->cacheRepository->remember($this->config['cache']['name'], $this->config['cache']['time'], function () {
                return $this->variableModel->select('key', 'value', 'locale')->get();
            });

            $allByLocale = $this->locale ? $all->where('locale', $this->locale) : $all;

            $this->variables = $allByLocale->pluck('value', 'key')->toArray();
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
}
