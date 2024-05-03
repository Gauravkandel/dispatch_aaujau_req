<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarcodeFieldInTaskProofTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_proofs', function (Blueprint $table) {
            $table->tinyInteger('barcode')->default(1)->comment('1 for enable, 2 for disable');
            $table->tinyInteger('barcode_requried')->default(1)->comment('1 for requried, 2 for Not requried');
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
            
        });
    }
}
