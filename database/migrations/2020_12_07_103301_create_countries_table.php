<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function(Blueprint $table)
		{
			$table->id();
			$table->string('code', 5);
			$table->string('name', 56);
			$table->timestamps();
		});

		Schema::table('countries', function (Blueprint $table) {
			$table->index('code');
			$table->index('name');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('countries', function (Blueprint $table) {
			$table->dropIndex('code');
			$table->dropIndex('name');
		});*/
		
		Schema::drop('countries');		
	}

}
