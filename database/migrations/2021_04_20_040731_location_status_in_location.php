<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocationStatusInLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('phone_number', 24)->nullable();
            $table->string('email', 70)->nullable();
            $table->integer('location_status')->default(1)->comment('1 for active, 0 deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('email');
            $table->dropColumn('location_status');
        });
    }
}
