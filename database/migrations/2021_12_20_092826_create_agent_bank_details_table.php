<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('payout_option_id')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_account_number')->nullable();
            $table->string('beneficiary_ifsc')->nullable();
            $table->string('beneficiary_address')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 - active, 0 - inactive');
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
        Schema::dropIfExists('agent_bank_details');
    }
}
