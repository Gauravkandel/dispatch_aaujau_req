<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('teams', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('manager_id')->unsigned()->nullable();
			$table->string('name', 40)->index();
			$table->smallInteger('location_accuracy');
			$table->smallInteger('location_frequency');
			$table->timestamps();
			$table->string('client_id', 10)->nullable();
		});

		Schema::table('teams', function (Blueprint $tab) {
			$tab->foreign('manager_id')->references('id')->on('managers')->onUpdate('cascade')->onDelete('set null');
			$tab->foreign('client_id')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
			$tab->index('location_accuracy');
			$tab->index('location_frequency');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('teams');

		/*Schema::table('teams', function (Blueprint $table) {
			$table->dropIndex('location_accuracy');
			$table->dropIndex('location_frequency');
		});*/

	}

}
