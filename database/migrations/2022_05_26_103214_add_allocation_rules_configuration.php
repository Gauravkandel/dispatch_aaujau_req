<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllocationRulesConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('allocation_rules', function (Blueprint $table) {
            $table->tinyInteger('job_consist_of_pickup_or_delivery')->default(0)->comment('1 yes, 0 no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allocation_rules', function (Blueprint $table) {
            $table->dropColumn('job_consist_of_pickup_or_delivery');
        });
    }
}
