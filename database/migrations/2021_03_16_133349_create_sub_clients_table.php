<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->index();
            $table->string('uid',50)->index();
            $table->string('email', 60)->unique()->nullable();
            $table->string('phone_number', 24)->nullable();
            $table->tinyInteger('status')->default(0)->comment('1 for active, 0 for pending, 2 for blocked, 3 for inactive');
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
        Schema::dropIfExists('sub_clients');
    }
}
