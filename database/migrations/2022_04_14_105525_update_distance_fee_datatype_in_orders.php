<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDistanceFeeDatatypeInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {            
            $table->decimal('base_distance', 6, 3)->nullable()->change();
            $table->decimal('duration_price', 6, 2)->nullable()->change();
            $table->decimal('waiting_price', 6, 2)->nullable()->change();
            $table->decimal('distance_fee', 6, 2)->nullable()->change();
            $table->decimal('cancel_fee', 6, 2)->nullable()->change();
            $table->decimal('distance_fee', 6, 2)->nullable()->change();
            
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
            //
        });
    }
}
