<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
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
            $table->bigIncrements('id');
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->string('group')->nullable();
            $table->unique(['key', 'group']);
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
};
