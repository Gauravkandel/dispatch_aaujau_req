<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs', function(Blueprint $table)
		{
			$table->id();
			$table->integer('queue');
			$table->text('payload');
			$table->tinyInteger('attempts')->default(0)->comment('1 yes, 0 no');
			$table->integer('reserved_at')->unsigned()->nullable();
			$table->integer('available_at')->unsigned();
			$table->integer('added_at')->unsigned();
			$table->timestamps();
		});

		Schema::table('jobs', function (Blueprint $table) {
			$table->index('queue');
			$table->index('attempts');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobs');
	}

}
