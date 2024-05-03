<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alteragenconnnecteds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('agent_connected_accounts')) {
            Schema::table('agent_connected_accounts', function (Blueprint $table) {
                if (Schema::hasColumn('agent_connected_accounts', 'is_primary')) {
                    $table->integer('is_primary')->default(1)->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
