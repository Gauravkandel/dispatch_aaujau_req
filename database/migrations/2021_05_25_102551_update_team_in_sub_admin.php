<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeamInSubAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_admin', function (Blueprint $table) {
            $table->tinyInteger('all_team_access')->default(0)->comment('1 for all/yes, 0 for no');
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
            $table->dropColumn('all_team_access');
        });
    }
}
