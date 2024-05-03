<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTaskProofs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_proofs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('image')->default(0)->comment('1 for enable 0 for disable');
            $table->tinyInteger('image_requried')->default(0)->comment('1 for requried 0 for not requried');;
            $table->tinyInteger('signature')->default(0)->comment('1 for enable 0 for disable');;
            $table->tinyInteger('signature_requried')->default(0)->comment('1 for requried 0 for not requried');
            $table->tinyInteger('note')->default(0)->comment('1 for enable 0 for disable');;
            $table->tinyInteger('note_requried')->default(0)->comment('1 for requried 0 for not requried');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_task_proofs');
    }
}
