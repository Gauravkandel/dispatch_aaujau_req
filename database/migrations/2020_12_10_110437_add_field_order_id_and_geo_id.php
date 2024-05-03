<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOrderIdAndGeoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('orders', function (Blueprint $table) {
        //     $table->bigInteger('pricing_rule_id')->nullable();
        //     $table->bigInteger('geo_id')->nullable();
        // });

        // Schema::table('orders', function (Blueprint $table) {
        //     $table->foreign('pricing_rule_id')->references('id')->on('price_rules')->onUpdate('cascade')->onDelete('set null');
        //     $table->foreign('geo_id')->references('id')->on('geos')->onUpdate('cascade')->onDelete('set null');
        // });
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
