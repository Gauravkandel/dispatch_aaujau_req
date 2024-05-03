<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('client_cards', function(Blueprint $table)
		{
			$table->id();
			$table->string('client_id', 10)->nullable();
			$table->string('card_details', 191);
			$table->boolean('is_primary')->default(0);
			$table->timestamps();
		});

		Schema::table('client_cards', function (Blueprint $table) {
			$table->foreign('client_id')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
			$table->index('is_primary');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('client_cards');
	}

}
