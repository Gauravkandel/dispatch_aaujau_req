<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 24)->nullable();
            $table->string('opt', 10)->nullable();
            $table->dateTime('valid_till')->nullable();
            $table->timestamps();
        });

        Schema::table('otps', function (Blueprint $table) {
            $table->foreign('phone')->references('phone_number')->on('agents')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otps');
    }
}
