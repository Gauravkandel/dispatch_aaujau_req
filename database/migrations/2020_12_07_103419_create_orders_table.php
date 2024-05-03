<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('customer_id')->unsigned()->nullable();
			$table->bigInteger('driver_id')->unsigned()->nullable();
			$table->dateTime('scheduled_date_time')->nullable();
			$table->text('key_value_set')->nullable();
			$table->string('conditions_tag_team', 100)->nullable();
			$table->string('conditions_tag_driver', 100)->nullable();
			$table->string('recipient_phone', 15)->nullable();
			$table->string('Recipient_email', 60)->nullable();
			$table->text('task_description')->nullable();
			$table->string('images_array')->nullable();
			$table->string('auto_alloction', 100)->nullable();
			$table->dateTime('order_time')->nullable();
			$table->string('order_type', 20)->nullable();
			$table->timestamps();
		});

		Schema::table('orders', function (Blueprint $tab) {
			$tab->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('set null');
			$tab->foreign('driver_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');

			$tab->index('scheduled_date_time');
			$tab->index('conditions_tag_team');
			$tab->index('conditions_tag_driver');
			$tab->index('recipient_phone');
			$tab->index('Recipient_email');
			$tab->index('order_time');
			$tab->index('order_type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('orders', function (Blueprint $tab) {
			$tab->dropIndex('scheduled_date_time');
			$tab->dropIndex('conditions_tag_team');
			$tab->dropIndex('conditions_tag_driver');
			$tab->dropIndex('recipient_phone');
			$tab->dropIndex('Recipient_email');
			$tab->dropIndex('order_time');
			$tab->dropIndex('order_type');
		});*/
		Schema::drop('orders');
	}

}
