<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropExtraTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::dropIfExists('geofence_schedule_dates');
        Schema::dropIfExists('geofence_schedule_weeks');
        Schema::dropIfExists('sub_admin');
        Schema::dropIfExists('agent_schedule_dates');
        Schema::dropIfExists('agent_schedule_weeks');
        Schema::dropIfExists('roles');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('geofence_schedule_dates', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned()->nullable();
			$table->tinyInteger('not_working_today')->default(1)->comment('1 yes, 0 no');
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->date('date');
			$table->timestamps();
		});

		Schema::table('geofence_schedule_dates', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
			$table->index('not_working_today');
			$table->index('date');
		});


        Schema::create('geofence_schedule_weeks', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned()->nullable();
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->string('day_of_week', 20)->index();
			$table->timestamps();
		});

		Schema::table('geofence_schedule_weeks', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
		});

        Schema::create('sub_admin', function (Blueprint $table) 
        {
			$table->id();
			$table->string('name', 50);
			$table->string('email', 60);
			$table->string('phone_number', 24)->nullable();
			$table->string('password');
			$table->tinyInteger('status')->default(2)->comment('1 for active, 2 for inactive');		
            $table->tinyInteger('all_team_access')->default(0)->comment('1 for all/yes, 0 for no');	
			$table->timestamps();
		});

        Schema::create('agent_schedule_dates', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned()->nullable();
			$table->tinyInteger('not_working_today')->default(1)->comment('1-yes, 0-no');
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->dateTime('date');
			$table->timestamps();
		});

		Schema::table('agent_schedule_dates', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
			$table->index('not_working_today');
			$table->index('date');
		});

        Schema::create('agent_schedule_weeks', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('agent_id')->unsigned()->nullable();
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->string('day_of_week', 20);
			$table->timestamps();
		});

		Schema::table('agent_schedule_weeks', function (Blueprint $table) {
			$table->foreign('agent_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');

		});


        Schema::create('roles', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 50)->unique();
			$table->string('slug', 50)->unique();
			$table->timestamps();
		});

    }
}
