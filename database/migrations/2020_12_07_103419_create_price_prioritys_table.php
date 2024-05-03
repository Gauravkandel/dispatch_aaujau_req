<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricePrioritysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_prioritys', function(Blueprint $table)
		{
			$table->id();
			$table->string('first', 15);
			$table->string('second', 15);
			$table->string('third', 15);
			$table->string('fourth', 15);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('price_prioritys');
	}

}
