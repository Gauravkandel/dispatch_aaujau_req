<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableExtraRosterData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_details', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone_number');
            $table->string('sort_name');
            $table->string('address');
            $table->string('lat');
            $table->string('long');
            $table->integer('task_count');
            $table->bigInteger('unique_id')->unsigned()->nullable();
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
        Schema::dropIfExists('rosetr_details');
    }
}
