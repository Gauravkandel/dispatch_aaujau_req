<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('client_payments', function(Blueprint $table)
		{
			$table->id();
			$table->string('client_id', 10)->nullable();
			$table->bigInteger('payment_id')->unsigned()->nullable();
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->dateTime('date');
			$table->decimal('amount', 10, 2)->default(0.00);
			$table->timestamps();
		});

		Schema::table('client_payments', function (Blueprint $table) {
			$table->foreign('client_id')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
			//$table->foreign('payment_id')->references('id')->on('payments')->onUpdate('cascade')->onDelete('set null');
			$table->index('date');
			$table->index('amount');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('client_payments', function (Blueprint $table) {
			$table->dropIndex('date');
			$table->dropIndex('amount');
		});*/
		Schema::drop('client_payments');
	}

}
