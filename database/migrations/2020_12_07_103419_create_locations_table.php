<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function(Blueprint $table)
		{
			$table->id();
			$table->decimal('latitude', 10, 8)->default(0);
			$table->decimal('longitude', 12, 8)->default(0);
			$table->string('short_name', 50)->nullable();
			$table->string('address', 100)->nullable();
			$table->integer('post_code')->nullable();
			$table->bigInteger('customer_id')->unsigned()->nullable();
			$table->timestamps();
		});

		Schema::table('locations', function (Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');

			$table->index('short_name');
			$table->index('post_code');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('locations', function (Blueprint $table) {
			$table->dropIndex('short_name');
			$table->dropIndex('post_code');
		});*/
		Schema::drop('locations');
	}

}
