<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('order_id')->unsigned()->nullable();
			$table->bigInteger('dependent_task_id')->unsigned()->nullable();
			$table->bigInteger('task_type_id')->unsigned()->nullable();
			$table->bigInteger('location_id')->unsigned()->nullable();
			$table->string('appointment_duration', 15)->nullable();
			$table->bigInteger('pricing_rule_id')->unsigned()->nullable();
			$table->integer('distance')->nullable();
			$table->dateTime('assigned_time')->nullable();
			$table->dateTime('accepted_time')->nullable();
			$table->dateTime('declined_time')->nullable();
			$table->dateTime('started_time')->nullable();
			$table->dateTime('reached_time')->nullable();
			$table->dateTime('failed_time')->nullable();
			$table->dateTime('cancelled_time')->nullable();
			$table->tinyInteger('cancelled_by_admin_id')->default(0)->comment('1 for yes, 0 for no');
			$table->dateTime('Completed_time')->nullable();
			$table->string('task_status', 20)->default(0)->comment('pending, delivered, on the way, ready for delivery, ready for departure');
			$table->string('allocation_type', 20);
			$table->timestamps();
			
		});

		Schema::table('tasks', function (Blueprint $tab) {
		  $tab->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
		  $tab->foreign('dependent_task_id')->references('id')->on('tasks')->onUpdate('cascade')->onDelete('cascade');
		  $tab->foreign('task_type_id')->references('id')->on('task_types')->onUpdate('cascade')->onDelete('cascade');
		  $tab->foreign('location_id')->references('id')->on('locations')->onUpdate('cascade')->onDelete('cascade');
		  $tab->foreign('pricing_rule_id')->references('id')->on('price_rules')->onUpdate('cascade')->onDelete('cascade');

			$tab->index('distance');
			$tab->index('cancelled_by_admin_id');
			$tab->index('Completed_time');
			$tab->index('allocation_type');
			$tab->index('task_status');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}
