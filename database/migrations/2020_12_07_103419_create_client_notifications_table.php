<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('client_notifications', function(Blueprint $table)
		{
			$table->id();
			$table->string('client_id', 10)->nullable();
			$table->string('webhook_url', 150)->nullable();
			$table->tinyInteger('request_recieved_sms')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('request_received_email')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('request_recieved_webhook')->default(0)->comment('1 for yes, 0 for no');
			$table->bigInteger('notification_event_id')->unsigned();
			$table->timestamps();
		});

		Schema::table('client_notifications', function (Blueprint $table) {
			$table->foreign('client_id')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');

			$table->index('request_recieved_sms');
			$table->index('request_received_email');
			$table->index('request_recieved_webhook');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('client_notifications', function (Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');

			$table->dropIndex('request_recieved_sms');
			$table->dropIndex('request_received_email');
			$table->dropIndex('request_recieved_webhook');
		});*/
		Schema::drop('client_notifications');
	}

}
