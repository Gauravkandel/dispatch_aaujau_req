<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 50);
			$table->string('email', 60)->unique();
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password', 191);
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
		});

		Schema::table('users', function (Blueprint $table) {
			$table->index('name');
			$table->index('email');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('users', function (Blueprint $table) {
		    $table->dropIndex('name');
			$table->dropIndex('email');
		});*/
		Schema::drop('users');
	}

}
