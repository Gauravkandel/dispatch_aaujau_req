<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubAdminPermissionsTableForAcl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sub_admin_id');
            $table->bigInteger('permission_id');					
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
        Schema::table('sub_admin_permissions', function (Blueprint $table) {
            Schema::drop('sub_admin_permissions');
        });
    }
}
