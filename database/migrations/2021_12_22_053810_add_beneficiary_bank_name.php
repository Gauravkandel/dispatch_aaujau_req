<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeneficiaryBankName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_bank_details', function (Blueprint $table) {
            $table->string('beneficiary_bank_name')->nullable()->after('beneficiary_account_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_bank_details', function (Blueprint $table) {
            $table->dropColumn('beneficiary_bank_name');
        });
    }
}
