<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

class AllVariable extends Command
{
    protected $signature = 'variable:all
                {--langcode= : The language code}
                {--cache=true : Use cache}';

    protected $description = 'Get all variables';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $useCache = in_array($this->option('cache'), ['0', 'false', false]) ? false : true;
        $variables = $variableMng
            ->all($this->option('langcode'), $useCache)
            ->toArray();

        print_r($variables);
    }
}
