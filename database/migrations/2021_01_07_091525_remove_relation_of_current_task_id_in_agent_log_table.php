<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRelationOfCurrentTaskIdInAgentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_logs', function (Blueprint $table) {
            $table->dropForeign('agent_logs_agent_id_foreign');
            $table->bigInteger('current_task_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_logs', function (Blueprint $table) {
            //
        });
    }
}
