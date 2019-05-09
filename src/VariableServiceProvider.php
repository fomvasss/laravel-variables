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
        ], 'variables-config');

        if (! class_exists('CreateVariablesTable')) {
             $timestamp = date('Y_m_d_His', time());

             $this->publishes([
                 __DIR__.'/../database/migrations/create_variables_table.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_variables_table.php",
             ], 'variables-migrations');
        }

        $this->replaceConfigsWithVariables();

        if ($this->app->runningInConsole()) {
             $this->commands([
                 Commands\AllVariable::class,
                 Commands\GetVariable::class,
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
            return new VariableManager(new Variable, $cacheRepo, $this->app);
        });
    }

    protected function replaceConfigsWithVariables()
    {
        $this->app->booted(function () {

            // package config
            $config = $this->app['config']->get('variables');

            // replace configs with variables
            $variableConfig = $config['variable_config'];

            try {
                $variables = $this->app->make(VariableManagerContract::class)->all();

                foreach ($variables as $varKey => $varValue) {
                    if (! empty($variableConfig[$varKey])) {
                        $this->app['config']->set($variableConfig[$varKey], $varValue);
                    }
                    if (! empty($config['config_key_for_vars'])) {
                        $this->app['config']->set($config['config_key_for_vars'] . '.' . $varKey, $varValue);
                    }
                }
            } catch (\Exception $exception) {
                $this->app['log']->info($exception->getMessage());
            }

        });
    }
}
