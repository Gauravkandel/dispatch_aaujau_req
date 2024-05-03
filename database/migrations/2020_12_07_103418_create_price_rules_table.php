<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_rules', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 50);
			$table->dateTime('start_date_time');
			$table->dateTime('end_date_time');
			$table->tinyInteger('is_default')->nullable()->comment('1 yes, 0 no');
			$table->bigInteger('geo_id')->unsigned()->nullable();
			$table->bigInteger('team_id')->unsigned()->nullable();
			$table->bigInteger('team_tag_id')->unsigned()->nullable();
			$table->bigInteger('driver_tag_id')->unsigned()->nullable();
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
			$table->timestamps();
		});

		Schema::table('price_rules', function (Blueprint $tab) {

			$tab->foreign('geo_id')->references('id')->on('geos')->onUpdate('cascade')->onDelete('set null');
			$tab->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('set null');
			$tab->foreign('team_tag_id')->references('id')->on('tags_for_teams')->onUpdate('cascade')->onDelete('set null');
			$tab->foreign('driver_tag_id')->references('id')->on('tags_for_agents')->onUpdate('cascade')->onDelete('set null');

			$tab->index('name');
			$tab->index('start_date_time');
			$tab->index('end_date_time');
			$tab->index('is_default');
			$tab->index('base_price');
			$tab->index('base_duration');
			$tab->index('base_distance');
			$tab->index('base_waiting');
			$tab->index('duration_price');
			$tab->index('waiting_price');
			$tab->index('distance_fee');
			$tab->index('cancel_fee');
			$tab->index('agent_commission_percentage');
			$tab->index('agent_commission_fixed');
			$tab->index('freelancer_commission_percentage');
			$tab->index('freelancer_commission_fixed');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('price_rules');
	}

}
