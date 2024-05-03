<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addfacecolumntaskproofs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_proofs', function (Blueprint $table) {
            $table->tinyInteger('face')->default(0);
            $table->tinyInteger('face_requried')->default(0);
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
