<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentBankDetailIdToPayout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_payouts', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_bank_detail_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_payouts', function (Blueprint $table) {
            $table->dropColumn('agent_bank_detail_id');
        });
    }
}
