<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

class CacheClearVariable extends Command
{
    protected $signature = 'variable:cache-clear';

    protected $description = 'Cache clear for all variables';

    public function handle()
    {
        app(\Fomvasss\Variable\VariableManagerContract::class)->cacheClear();

        $this->info('Variable cache cleared!');
    }
}
