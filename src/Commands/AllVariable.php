<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

/**
 * Class TaxonomyMigrate
 *
 * @package \Fomvasss\Taxonomy
 */
class AllVariable extends Command
{
    protected $signature = 'variable:get-all
                {locale? : The locale variable}';

    protected $description = 'Get all variables';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $variables = $variableMng->locale($this->argument('locale'))->all();
        print_r($variables);
    }
}
