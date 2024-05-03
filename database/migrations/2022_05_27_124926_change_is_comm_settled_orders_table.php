<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class changeIsCommSettledOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_comm_settled']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('is_comm_settled')->default(0)->comment('0=>Not settled, 1=> Processing, 2=> settled');
        });
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
