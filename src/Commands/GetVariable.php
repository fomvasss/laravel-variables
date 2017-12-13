<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

/**
 * Class TaxonomyMigrate
 *
 * @package \Fomvasss\Taxonomy
 */
class GetVariable extends Command
{
    protected $signature = 'variable:get
                {name : The name variable}
                {locale? : The locale variable}';

    protected $description = 'Get one variable';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $variable = $variableMng->locale($this->argument('locale'))->get($this->argument('name'), '');
        print_r($variable . "\n");
    }
}
