<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agent_logs', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned();
			$table->bigInteger('current_task_id')->unsigned();
			$table->decimal('lat', 10, 8)->default(0);
			$table->decimal('long', 12, 8)->default(0);
			$table->smallInteger('battery_level')->default(0);
			$table->string('android_version', 20)->nullable();
			$table->string('app_version', 10)->nullable();
			$table->string('current_speed', 20)->nullable();
			$table->tinyInteger('on_route')->default(1)->comment('for task -> 1 for Yes,n for 0');
			$table->timestamps();
		});

		Schema::table('agent_logs', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('cascade');

			$table->index('lat');
			$table->index('long');
			$table->index('on_route');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('agent_logs', function (Blueprint $table) {
			$table->dropIndex('lat');
			$table->dropIndex('long');
			$table->dropIndex('on_route');
		});*/
		Schema::drop('agent_logs');
	}

}
