<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

/**
 * Class TaxonomyMigrate
 *
 * @package \Fomvasss\Taxonomy
 */
class SetVariable extends Command
{
    protected $signature = 'variable:set 
                {name : The name of the variable} 
                {value? : The value variable}
                {locale? : The locale variable}';

    protected $description = 'Sett all variables';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $variable = $variableMng->locale($this->argument('locale'))->set(
            $this->argument('name'),
            $this->argument('value')
        );

        if ($variable) {
            $cacheName = 'fomvasss.variables.cache';
            $this->call('cache:forget', ['key' => $cacheName]);
            $this->info("Variable set successful`{$this->argument('name')}`");
        } else {
            $this->info("Variable set error `{$this->argument('name')}`");
        }
    }
}
