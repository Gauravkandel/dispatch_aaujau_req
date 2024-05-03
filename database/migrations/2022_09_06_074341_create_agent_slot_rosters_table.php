<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentSlotRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_slot_rosters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('slot_id')->unsigned()->nullable();
            $table->bigInteger('agent_id')->unsigned()->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->dateTime('schedule_date');
            $table->longText('memo')->nullable();
            $table->enum('booking_type', ['blocked', 'new_booking','working_hours']);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->index('schedule_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_slot_rosters');
    }
}
