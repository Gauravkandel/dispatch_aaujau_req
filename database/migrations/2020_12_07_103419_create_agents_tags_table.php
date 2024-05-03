<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents_tags', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned()->nullable();
			$table->bigInteger('tag_id')->unsigned()->nullable();
			$table->timestamps();
		});

		Schema::table('agents_tags', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('tags_for_agents')->onUpdate('cascade')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agents_tags');
	}

}
