<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('team_id')->unsigned()->nullable();
			$table->string('name', 50);
			$table->string('profile_picture', 150);
			$table->string('type', 20)->comment('Freelancer,In house');
			$table->bigInteger('vehicle_type_id')->unsigned()->nullable();
			$table->string('make_model', 60)->nullable();
			$table->string('plate_number', 15)->nullable();
			$table->string('phone_number', 24)->nullable();
			$table->string('color', 15);
			$table->tinyInteger('is_activated')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('is_available')->default(0)->comment('1 for yes, 0 for no');
			$table->string('device_type', 15)->nullable();
			$table->string('device_token')->nullable();
			$table->string('access_token')->nullable();
			$table->timestamps();
		});

		Schema::table('agents', function (Blueprint $table) {
			$table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('set null');
			$table->foreign('vehicle_type_id')->references('id')->on('vehicle_types')->onUpdate('cascade')->onDelete('set null');

			$table->index('phone_number');
			$table->index('is_available');
			$table->index('is_activated');
			$table->index('access_token');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('agents', function (Blueprint $table) {
			$table->dropIndex('phone_number');
			$table->dropIndex('is_available');
			$table->dropIndex('is_activated');
			$table->dropIndex('access_token');
		});*/
		Schema::drop('agents');
	}

}
