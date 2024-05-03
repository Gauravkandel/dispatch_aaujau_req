<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeofenceScheduleDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geofence_schedule_dates', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned()->nullable();
			$table->tinyInteger('not_working_today')->default(1)->comment('1 yes, 0 no');
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->date('date');
			$table->timestamps();
		});

		Schema::table('geofence_schedule_dates', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
			$table->index('not_working_today');
			$table->index('date');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('geofence_schedule_dates', function (Blueprint $table) {
			$table->dropIndex('not_working_today');
			$table->dropIndex('date');
		});*/

		Schema::drop('geofence_schedule_dates');
	}

}
