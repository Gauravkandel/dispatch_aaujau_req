<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 50);
			$table->string('email', 60);
			$table->string('phone_number', 24)->nullable();
			$table->string('password');
			$table->bigInteger('country_id')->unsigned()->nullable();
			$table->string('timezone', 25)->nullable();
			$table->string('custom_domain', 20)->nullable();
			$table->tinyInteger('is_deleted')->default(0);
			$table->tinyInteger('is_blocked')->default(0);
			$table->string('database_path')->nullable();
			$table->string('database_name', 50)->nullable();
			$table->string('database_username', 50)->nullable();
			$table->string('database_password', 50)->nullable();
			$table->string('logo', 100)->nullable();
			$table->string('company_name', 50)->nullable();
			$table->string('company_address', 100)->nullable();
			$table->tinyInteger('status')->default(0)->comment('1 for active, 0 for pending, 2 for blocked, 3 for inactive');
			$table->string('code', 10)->unique();
			$table->timestamps();
		});

		Schema::table('clients', function (Blueprint $table) {

		  $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null');

			$table->index('phone_number');
			$table->index('custom_domain');
			$table->index('is_deleted');
			$table->index('is_blocked');
			$table->index('database_name');
			$table->index('company_name');
			$table->index('status');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('clients', function (Blueprint $table) {
			$table->dropIndex('phone_number');
			$table->dropIndex('custom_domain');
			$table->dropIndex('is_deleted');
			$table->dropIndex('is_blocked');
			$table->dropIndex('database_name');
			$table->dropIndex('company_name');
			$table->dropIndex('status');
		});*/

		Schema::drop('clients');
	}

}
