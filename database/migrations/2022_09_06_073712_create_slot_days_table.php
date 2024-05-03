<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlotDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slot_days', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('slot_id')->unsigned()->nullable();
            $table->tinyInteger('day')->default(0)->comment('1 sunday, 2 monday, 3 tuesday, 4 wednesday, 5 thursday, 6 friday, 7 saturday');
            $table->timestamps();
            $table->foreign('slot_id')->references('id')->on('agent_slots')->onDelete('cascade');
            $table->index('day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slot_days');
    }
}
