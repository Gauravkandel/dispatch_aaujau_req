<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocketToClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->integer('admin_chat')->default(2);
            $table->integer('driver_chat')->default(2);
            $table->integer('customer_chat')->default(2);
            $table->string('socket_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('admin_chat');
            $table->dropColumn('driver_chat');
            $table->dropColumn('customer_chat');
            $table->dropColumn('socket_url');
        });
    }
}
