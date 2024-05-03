<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuperadminInClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->tinyInteger('is_superadmin')->default(1)->comment('1 for yes, 0 for no');
            $table->tinyInteger('all_team_access')->default(1)->comment('1 for all/yes, 0 for no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('is_superadmin');
            $table->dropColumn('all_team_access');
        });
    }
}
