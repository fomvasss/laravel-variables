<?php

/*
|--------------------------------------------------------------------------
|  Laravel Variables
|--------------------------------------------------------------------------
|
*/
return [

    'model_name' => \Fomvasss\Variable\Models\Variable::class,

    'table_name' => 'variables',

    /* -----------------------------------------------------------------
     |  Root key for usage vars in Laravel config, example.
     |  Usage: config('vars.some_var')
     |  If empty this - option OFF
     | -----------------------------------------------------------------
     */
    'config_key_for_vars' => 'vars',

    /* -----------------------------------------------------------------
     |  Dynamic replace config keys with variables
     | -----------------------------------------------------------------
     */
    'variable_config' => [
        'app_name' => 'app.name',                   // config('app.name')
        'app_description' => 'app.description',     // config('app.description')
        'some_var' => 'services.some.var',          // config('services.some.var')
    ],

    /*
     * Apply global scopes for variable model.
     */
    'prepare_scopes' => [
        //...
    ],

    /* -----------------------------------------------------------------
     |  Use delimiter in var key for array value
     | -----------------------------------------------------------------
     */
    'array_delimiter' => '.',

    /* -----------------------------------------------------------------
     |  Use delimiter in var key (example: uk|title, en|title)
     | -----------------------------------------------------------------
     */
    'group_key_delimiter' => '|',

    /* -----------------------------------------------------------------
     |  Cache settings for vars
     | -----------------------------------------------------------------
     */
    'cache' => [

        'is_use_cache' => true,

        'autoclear' => true, // ex: After saved/updated var model

        'time' => 3600, //sec.

        'name' => 'laravel.variables.cache',
    ],

    /*
     * If a variable has not been set for a given group,
     * any other group will be chosen instead.
     */
    'fallback_any' => false,
];
