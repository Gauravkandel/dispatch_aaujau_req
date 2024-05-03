<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notification_events', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('notification_type_id')->unsigned()->nullable();
			$table->string('name', 60);
			$table->string('description', 100)->nullable();
			$table->timestamps();
		});

		Schema::table('notification_events', function (Blueprint $table) {
			$table->foreign('notification_type_id')->references('id')->on('notification_types')->onUpdate('cascade')->onDelete('set null');

			$table->index('name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notification_events');
	}

}
