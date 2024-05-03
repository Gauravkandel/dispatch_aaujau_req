<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_types', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 50)->unique();
			$table->string('client_id', 10)->nullable();
			$table->timestamps();
		});

		Schema::table('task_types', function (Blueprint $tab) {
			$tab->foreign('client_id')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_types');
	}

}
