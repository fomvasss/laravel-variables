<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

class GetVariable extends Command
{
    protected $signature = 'variable:get
                {key : The variable key}
                {--langcode= : The language code}
                {--cache=true : Use cache}';

    protected $description = 'Get single variable';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $useCache = in_array($this->option('cache'), ['0', 'false', false]) ? false : true;
        $variable = $variableMng
//            ->setLang($this->option('langcode'))
//            ->setIsUseCache($this->option('cache'))
            ->get($this->argument('key'), null, $this->option('langcode'), $useCache);

        print_r($variable . "\n");
    }
}
