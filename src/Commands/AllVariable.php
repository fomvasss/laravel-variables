<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

class AllVariable extends Command
{
    protected $signature = 'variable:all
                {--group= : The group name}
                {--cache=true : Use cache}';

    protected $description = 'Get all variables';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $useCache = in_array($this->option('cache'), ['0', 'false', false]) ? false : true;
        $variables = $variableMng
            ->all($this->option('group'), $useCache);

        $table = [];
        foreach ($variables as $item) {
            $table[] = [$item->id, $item->key, $item->value, $item->group];
        }

        $this->getOutput()->newLine();
        $this->alert('Variables');
        $this->table(['#', 'Key', 'Valuy', 'Group',], $table);
        $this->getOutput()->newLine(2);
    }
}
