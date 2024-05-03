<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTypeInTaskProofsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_proofs', function (Blueprint $table) {
            $table->tinyInteger('type')->default(1)->comment('1 for pickup, 2 for drop, 3 for appointment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_proofs', function (Blueprint $table) {
            //
        });
    }
}
