<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationDistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_distance', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('from_loc_id');
            $table->bigInteger('to_loc_id');
			$table->bigInteger('distance')->comment('in meters');
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
        Schema::table('location_distance', function (Blueprint $table) {
            Schema::drop('location_distance');
        });
    }
}
