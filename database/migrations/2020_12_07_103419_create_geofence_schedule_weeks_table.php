<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeofenceScheduleWeeksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geofence_schedule_weeks', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned()->nullable();
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->string('day_of_week', 20)->index();
			$table->timestamps();
		});

		Schema::table('geofence_schedule_weeks', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geofence_schedule_weeks');
	}

}
