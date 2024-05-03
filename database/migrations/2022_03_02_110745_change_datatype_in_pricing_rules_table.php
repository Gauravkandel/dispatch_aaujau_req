<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeInPricingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->decimal('base_distance', 10, 2)->nullable()->change();
            $table->decimal('duration_price', 10, 2)->nullable()->change();
            $table->decimal('waiting_price', 10, 2)->nullable()->change();
            $table->decimal('distance_fee', 10, 2)->nullable()->change();
            $table->decimal('cancel_fee', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            //
        });
    }
}
