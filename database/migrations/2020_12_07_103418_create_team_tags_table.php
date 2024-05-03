<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('team_tags', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('tag_id')->unsigned()->nullable();
			$table->bigInteger('team_id')->unsigned()->nullable();
			$table->timestamps();
		});

		Schema::table('team_tags', function (Blueprint $tab) {
			$tab->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
			$tab->foreign('tag_id')->references('id')->on('tags_for_teams')->onUpdate('cascade')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('team_tags');
	}

}
