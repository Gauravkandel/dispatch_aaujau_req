<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTaskProofsToAddQrcordeWithOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_proofs', function (Blueprint $table) {
            $table->tinyInteger('qrcode')->default(0)->comment('1 for enable, 2 for disable');
            $table->tinyInteger('qrcode_requried')->default(0)->comment('1 for requried, 2 for Not requried');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_qrcorde_with_order', function (Blueprint $table) {
            //
        });
    }
}
