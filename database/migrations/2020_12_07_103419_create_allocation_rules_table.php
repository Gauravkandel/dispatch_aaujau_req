<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('allocation_rules', function(Blueprint $table)
		{
			$table->id();
			$table->string('client_id', 10)->nullable();
			$table->tinyInteger('manual_allocation')->default(1)->comment('1 yes, 0 no');
			$table->char('auto_assign_logic', 25)->comment('one by one,request sent to all, request sent batch wise, one by one forced, round robin, forced to nearest');
			$table->char('request_expiry', 20)->comment('one by one or batch');
			$table->integer('number_of_retries')->default(0);
			$table->integer('task_priority')->default(0);
			$table->decimal('start_radius', 10, 3)->nullable();
			$table->string('start_before_task_time', 20)->nullable();
			$table->decimal('increment_radius', 10, 3)->nullable();
			$table->decimal('maximum_radius', 10, 3)->nullable();
			$table->integer('maximum_batch_size')->nullable();
			$table->integer('maximum_batch_count')->nullable();
			$table->integer('maximum_task_per_person')->nullable();
			$table->tinyInteger('self_assign')->default(0)->comment('1 yes, 0 no');
			$table->timestamps();
			
		});

		Schema::table('allocation_rules', function (Blueprint $table) {

			$table->foreign('client_id')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');

			$table->index('manual_allocation');
			$table->index('auto_assign_logic');
			$table->index('task_priority');
			$table->index('maximum_task_per_person');
			$table->index('self_assign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('allocation_rules', function (Blueprint $table) {
			$table->dropIndex('manual_allocation');
			$table->dropIndex('auto_assign_logic');
			$table->dropIndex('task_priority');
			$table->dropIndex('maximum_task_per_person');
			$table->dropIndex('self_assign');
		});*/
		Schema::drop('allocation_rules');
	}

}
