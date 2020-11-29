<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

class SaveVariable extends Command
{
    protected $signature = 'variable:save
                {key : The variable key}
                {value : The variable value}
                {langcode? : The language code}';

    protected $description = 'Update or create single variable';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $variable = $variableMng
            ->save($this->argument('key'), $this->argument('value'), $this->argument('langcode'));

        $this->info('Variable successfully saved!');
    }
}
