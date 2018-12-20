<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
        {
            $tableName = config('variables.table_name');

            Schema::create($tableName, function (Blueprint $table) {
				$table->increments('id');
                $table->string('key')->index();
                $table->text('value')->nullable();
//                $table->string('description')->nullable();
                $table->string('locale')->nullable();

                $table->unique(['key', 'locale']);
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            $tableName = config('variables.table_name');

            Schema::drop($tableName);
        }
}
