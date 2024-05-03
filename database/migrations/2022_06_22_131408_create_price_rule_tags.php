<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRuleTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_rule_tags', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('pricing_rule_id')->unsigned()->nullable();
            $table->bigInteger('tag_id')->unsigned()->nullable();
			$table->char('identity', 30);
			$table->timestamps();
			
		});

		Schema::table('price_rule_tags', function (Blueprint $tab) {
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
        Schema::dropIfExists('price_rule_tags');
    }
}
