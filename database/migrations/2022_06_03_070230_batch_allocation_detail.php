<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchAllocationDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_allocation_details', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no');
            $table->integer('order_id')->nullable();
            $table->integer('geo_id')->nullable();
            $table->integer('agent_id')->nullable();
			$table->dateTime('order_time')->nullable();
			$table->string('order_type', 20)->comment('p: Pickup D: Delivery')->nullable();
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
        Schema::dropIfExists('batch_allocation_details');
    }
}
