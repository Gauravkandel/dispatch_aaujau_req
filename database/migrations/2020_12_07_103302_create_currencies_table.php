<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currencies', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 50);
			$table->integer('priority')->default(0);
			$table->string('iso_code', 5);
			$table->string('symbol', 10);
			$table->string('subunit', 20);
			$table->integer('subunit_to_unit');
			$table->tinyInteger('symbol_first');
			$table->string('html_entity', 25);
			$table->string('decimal_mark', 10);
			$table->string('thousands_separator', 10);
			$table->smallInteger('iso_numeric')->default(0);
			$table->timestamps();
		});

		Schema::table('currencies', function (Blueprint $table) {
			$table->index('name');
			$table->index('priority');
			$table->index('iso_code');
			$table->index('iso_numeric');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('currencies', function (Blueprint $table) {
			$table->index('name');
			$table->index('priority');
			$table->index('iso_code');
			$table->index('iso_numeric');
		});*/

		Schema::drop('currencies');
	}

}
