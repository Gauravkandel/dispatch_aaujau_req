<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_temps', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('geo_id');
            $table->integer('order_order');
            $table->integer('task_id');
            $table->decimal('task_lat',10,8)->default(0);
            $table->decimal('task_long',10,8)->default(0);
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
        Schema::dropIfExists('order_temps');
    }
}
