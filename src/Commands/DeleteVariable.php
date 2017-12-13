<?php

namespace Fomvasss\Variable\Commands;

use Illuminate\Console\Command;

/**
 * Class TaxonomyMigrate
 *
 * @package \Fomvasss\Taxonomy
 */
class DeleteVariable extends Command
{
    protected $signature = 'variable:delete
                {name : The name of the variable}
                {locale? : The locale variable}';

    protected $description = 'Delete a variable';

    public function handle()
    {
        $variableMng = app(\Fomvasss\Variable\VariableManagerContract::class);

        $variable = $variableMng->locale($this->argument('locale'))->delete(
            $this->argument('name')
        );

        if ($variable) {
            $cacheName = 'fomvasss.variables.cache';
            $this->call('cache:forget', ['key' => $cacheName]);
            $this->info("Variable delete successful `{$this->argument('name')}`");
        } else {
            $this->info("Variable delete error `{$this->argument('name')}`");
        }
    }
}
