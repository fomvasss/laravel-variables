<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

class GetVariable extends Command
{
    protected $signature = 'variable:get
                {key : The variable key}
                {--group= : The group name}
                {--cache=true : Use cache}';

    protected $description = 'Get single variable';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $useCache = in_array($this->option('cache'), ['0', 'false', false]) ? false : true;
        $variable = $variableMng
            ->get($this->argument('key'), null, $this->option('group'), $useCache);

        $this->alert("Variable [{$this->argument('key')}]:");
        $this->info($variable);
        $this->getOutput()->newLine();
    }
}
