<?php

namespace Fomvasss\Variable;

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

        if ($this->app->runningInConsole()) {
             $this->commands([
                 Commands\SetVariable::class,
                 Commands\AllVariable::class,
                 Commands\GetVariable::class,
                 Commands\DeleteVariable::class,
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

        $this->app->singleton(VariableManagerContract::class, VariableManager::class);
    }
}
