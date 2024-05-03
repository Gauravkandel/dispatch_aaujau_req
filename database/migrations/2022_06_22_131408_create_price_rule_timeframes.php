<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRuleTimeframes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_rule_timeframes', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('pricing_rule_id')->unsigned()->nullable();
			$table->tinyInteger('is_applicable')->nullable();
			$table->char('day_name', 30);
            $table->time('start_time', $precision = 0);
            $table->time('end_time', $precision = 0);
			$table->timestamps();
			
		});

		Schema::table('price_rule_timeframes', function (Blueprint $tab) {
		  $tab->foreign('pricing_rule_id')->references('id')->on('price_rules')->onUpdate('cascade')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_rule_timeframes');
    }
}
