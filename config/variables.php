<?php

/*
|--------------------------------------------------------------------------
|  Laravel Variables
|--------------------------------------------------------------------------
|
*/
return [

    'model' => \Fomvasss\Variable\Variable::class,

    'table_name' => 'variables',

    /* -----------------------------------------------------------------
     |  Root key for config, example: 'vars'
     |  Use: config('vars.some_var')
     |  If empty this - option OFF
     | -----------------------------------------------------------------
     */
    'config_key_for_vars' => '',

    /* -----------------------------------------------------------------
     |  Replace configs with variables
     | -----------------------------------------------------------------
     */
    'variable_config' => [
        'app_name' => 'app.name',                   // config('app.name')
        'app_description' => 'app.description',     // config('app.description')
        'some_var' => 'services.some.var',          // config('services.some.var')
    ],

    /* -----------------------------------------------------------------
     |  Cache settings for vars
     | -----------------------------------------------------------------
     */
    'cache' => [

        'time' => 360,

        'name' => 'laravel.variables.cache',
    ]
];
