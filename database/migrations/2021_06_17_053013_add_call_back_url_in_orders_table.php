<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallBackUrlInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (Schema::hasColumn('orders', 'call_back_url')){
            Schema::table('orders', function (Blueprint $table) {
             $table->dropColumn('call_back_url');
            });
        }
        Schema::table('orders', function (Blueprint $table) {
            $table->string('call_back_url',500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        if (Schema::hasColumn('orders', 'call_back_url'))
        {
            Schema::table('orders', function (Blueprint $table)
            {
                $table->dropColumn('call_back_url');
            });
        }

        
    }
}



