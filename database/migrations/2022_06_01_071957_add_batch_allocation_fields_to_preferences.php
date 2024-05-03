<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchAllocationFieldsToPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        if (Schema::hasColumn('allocation_rules', 'job_consist_of_pickup_or_delivery'))
        {
            Schema::table('allocation_rules', function (Blueprint $table)
            {
                $table->dropColumn('job_consist_of_pickup_or_delivery');
            });
        }

        Schema::table('client_preferences', function (Blueprint $table) {
            $table->integer('create_batch_hours')->nullable();
            $table->integer('maximum_route_per_job')->nullable();
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
        Schema::table('client_preferences', function (Blueprint $table) {
            //
        });
    }
}
