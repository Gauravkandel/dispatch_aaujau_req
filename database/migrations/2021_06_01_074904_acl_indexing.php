<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AclIndexing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_admin_permissions', function (Blueprint $table) {                        
            
            $table->bigInteger('sub_admin_id')->change();
			$table->bigInteger('permission_id')->change();

            $table->index('sub_admin_id')->change();
			$table->index('permission_id')->change();
            
        });

        Schema::table('sub_admin_team_permissions', function (Blueprint $table) {
            
            $table->bigInteger('sub_admin_id')->change();
			$table->bigInteger('team_id')->change();

            $table->index('sub_admin_id')->change();
			$table->index('team_id')->change();
            
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
            //
        });
    }
}
