<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskDriverTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_driver_tags', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('task_id')->unsigned()->nullable();
			$table->bigInteger('tag_id')->unsigned()->nullable();
			$table->timestamps();
        });

        Schema::table('task_driver_tags', function (Blueprint $tab) {
			$tab->foreign('task_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
			$tab->foreign('tag_id')->references('id')->on('tags_for_agents')->onUpdate('cascade')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_driver_tags');
    }
}
