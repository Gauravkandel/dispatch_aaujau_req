<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('client_preferences', function(Blueprint $table)
		{
			$table->id();
			$table->string('client_id', 10)->nullable();
			$table->string('theme', 15)->default('light')->comment('light,dark');
			$table->string('distance_unit', 10)->nullable()->comment('KM, miles');
			$table->bigInteger('currency_id')->unsigned()->nullable();
			$table->bigInteger('language_id')->unsigned()->nullable();
			$table->string('agent_name', 20)->nullable()->comment('name type for agent field - Driver, Service Provider etc.');
			$table->string('acknowledgement_type', 20)->default('Acknowledge')->comment('Acknowledge,Accept/Reject, None');
			$table->string('date_format', 15)->nullable();
			$table->tinyInteger('time_format')->default(1)->comment("1 for 24 format, 0 for 12 hour format");
			$table->string('map_type', 15)->default('google')->comment('google,mapbox');
			$table->string('map_key_1', 50)->nullable();
			$table->string('map_key_2', 50)->nullable();
			$table->string('sms_provider', 20)->nullable();
			$table->string('sms_provider_key_1', 50)->nullable()->comment('primary key');
			$table->string('sms_provider_key_2', 50)->nullable()->comment('secrate key');
			$table->bigInteger('allow_feedback_tracking_url')->default(0)->comment('1 yes, 0 no');
			$table->string('task_type', 30)->nullable();
			$table->string('order_id', 20)->nullable();
			$table->string('email_plan', 10)->default('free')->comment('free,paid');
			$table->string('domain_name', 50)->nullable();
			$table->string('personal_access_token_v1', 60)->nullable();
			$table->string('personal_access_token_v2', 60)->nullable();
			$table->timestamps();
		});


		Schema::table('client_preferences', function (Blueprint $tab) {

		  $tab->foreign('client_id')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
		  $tab->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('set null');
		  $tab->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade')->onDelete('set null');

		 	$tab->index('theme');
			$tab->index('distance_unit');
			$tab->index('map_type');
			$tab->index('task_type');
			$tab->index('email_plan');
			$tab->index('domain_name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		/*Schema::table('client_preferences', function (Blueprint $tab) {

		 	$table->dropIndex('theme');
			$table->dropIndex('distance_unit');
			$table->dropIndex('map_type');
			$table->dropIndex('task_type');
			$table->dropIndex('email_plan');
			$table->dropIndex('domain_name');
		});*/
		Schema::drop('client_preferences');
	}

}
