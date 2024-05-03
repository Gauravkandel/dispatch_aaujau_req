<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('managers', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 50);
			$table->string('email', 60);
			$table->string('phone_number', 24)->nullable();
			$table->string('password');
			$table->tinyInteger('can_create_task')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('can_edit_task_created')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('can_edit_all')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('can_manage_unassigned_tasks')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('can_edit_auto_allocation')->default(0)->comment('1 for yes, 0 for no');
			$table->bigInteger('client_id')->unsigned();
			$table->string('profile_picture');
			$table->timestamps();
		});

		Schema::table('managers', function (Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');

			$table->index('email');
			$table->index('phone_number');
			$table->index('can_create_task');
			$table->index('can_edit_task_created');
			$table->index('can_edit_all');
			$table->index('can_manage_unassigned_tasks');
			$table->index('can_edit_auto_allocation');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('managers');

		/*Schema::table('managers', function (Blueprint $table) {
			$table->dropIndex('email');
			$table->dropIndex('phone_number');
			$table->dropIndex('can_create_task');
			$table->dropIndex('can_edit_task_created');
			$table->dropIndex('can_edit_all');
			$table->dropIndex('can_manage_unassigned_tasks');
			$table->dropIndex('can_edit_auto_allocation');
		});*/

	}

}
