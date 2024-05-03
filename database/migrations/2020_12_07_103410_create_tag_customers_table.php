<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tag_customers', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('customer_id')->unsigned()->nullable();
			$table->bigInteger('tag_id')->unsigned()->nullable();
			$table->timestamps();
		});

		Schema::table('tag_customers', function (Blueprint $tab) {
			$tab->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
			$tab->foreign('tag_id')->references('id')->on('tags')->onUpdate('cascade')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tag_customers');
	}

}
