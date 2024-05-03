<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_types', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 20)->unique();
			$table->string('icon', 50)->nullable();
			$table->decimal('storage_width', 4, 3)->nullable();
			$table->decimal('storage_height', 4, 3)->nullable();
			$table->decimal('storage_length', 4, 3)->nullable();
			$table->decimal('fuel_cost', 10, 2)->nullable()->comment('per km');
			$table->timestamps();
		});

		Schema::table('vehicle_types', function (Blueprint $tab) {
			$tab->index('fuel_cost');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_types');
	}

}
