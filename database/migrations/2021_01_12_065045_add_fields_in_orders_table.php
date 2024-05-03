<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('base_price', 6, 2)->nullable();
			$table->string('base_duration', 15)->nullable();
			$table->decimal('base_distance', 4, 3)->nullable();
			$table->string('base_waiting', 15)->nullable();
			$table->decimal('duration_price', 4, 2)->nullable();
			$table->decimal('waiting_price', 4, 2)->nullable();
			$table->decimal('distance_fee', 4, 2)->nullable();
			$table->decimal('cancel_fee', 4, 2)->nullable();
			$table->smallInteger('agent_commission_percentage')->nullable();
			$table->smallInteger('agent_commission_fixed')->nullable();
			$table->smallInteger('freelancer_commission_percentage')->nullable();
            $table->smallInteger('freelancer_commission_fixed')->nullable();
            $table->decimal('actual_time',6,2)->nullable();
            $table->decimal('actual_distance',6,2)->nullable();
            $table->decimal('order_cost',6,2)->nullable();
            $table->decimal('driver_cost',6,2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            
        });
    }
}
