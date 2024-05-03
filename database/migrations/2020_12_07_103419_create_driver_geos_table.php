<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverGeosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('driver_geos', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('geo_id')->unsigned()->nullable();
			$table->bigInteger('driver_id')->unsigned()->nullable();
			$table->bigInteger('team_id')->unsigned()->nullable();
			$table->timestamps();
		});

		Schema::table('driver_geos', function (Blueprint $table) {
			$table->foreign('geo_id')->references('id')->on('geos')->onUpdate('cascade')->onDelete('set null');
			$table->foreign('driver_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
			$table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('set null');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('driver_geos');
	}

}
