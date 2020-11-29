<?php

namespace Fomvasss\Variable;

use Fomvasss\Variable\Models\Variable;
use Illuminate\Support\ServiceProvider;

class VariableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/variables.php' => config_path('variables.php'),
        ], 'config');

        if (! class_exists('CreateVariablesTable')) {
             $timestamp = date('Y_m_d_His', time());

             $this->publishes([
                 __DIR__.'/../database/migrations/create_variables_table.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_variables_table.php",
             ], 'migrations');
        }

        $this->replaceConfigsWithVariables();

        if ($this->app->runningInConsole()) {
             $this->commands([
                 Commands\AllVariable::class,
                 Commands\GetVariable::class,
                 Commands\SaveVariable::class,
                 Commands\CacheClearVariable::class,
             ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/variables.php', 'variables');

        $this->app->singleton(VariableManagerContract::class, function () {
            $cacheRepo = $this->app->make(\Illuminate\Cache\Repository::class);
            $variableModel = $this->app['config']->get('variables.model_name');
            
            return new VariableManager(new $variableModel, $cacheRepo, $this->app);
        });
    }

    protected function replaceConfigsWithVariables()
    {
        $this->app->booted(function () {

            // Package config
            $config = $this->app['config']->get('variables');

            // Dynamic replace config keys with variables
            $variableConfig = $config['variable_config'];

            try {
                $variables = $this->app->make(VariableManagerContract::class)->all();

                foreach ($variables as $var) {
                    if (! empty($variableConfig[$var->key])) {
                        $this->app['config']->set($variableConfig[$var->key], $var->value);
                    }
                    if (! empty($config['config_key_for_vars'])) {
                        $this->app['config']->set($config['config_key_for_vars'] . '.' . $var->key, $var->value);
                    }
                }
            } catch (\Exception $exception) {
                $this->app['log']->warning($exception->getMessage());
            }
        });
    }
}
