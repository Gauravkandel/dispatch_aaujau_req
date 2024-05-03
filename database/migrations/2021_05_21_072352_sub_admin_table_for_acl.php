<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubAdminTableForAcl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('sub_admin', function (Blueprint $table) 
        {
			$table->id();
			$table->string('name', 50);
			$table->string('email', 60);
			$table->string('phone_number', 24)->nullable();
			$table->string('password');
			$table->tinyInteger('status')->default(2)->comment('1 for active, 2 for inactive');			
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
        Schema::table('sub_admin', function (Blueprint $table) {
            Schema::drop('sub_admin');
        });
    }
}
