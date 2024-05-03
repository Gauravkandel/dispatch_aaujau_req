<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdToAgentSlotRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_slot_rosters', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->comment('order id')->after('agent_id');
            $table->unsignedBigInteger('order_number')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_slot_rosters', function (Blueprint $table) {
            //
        });
    }
}
