<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 20);
            $table->string('keyword', 20);
            $table->tinyInteger('status')->default(0)->comment(' 0 for no, 1 for yes');
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
        Schema::dropIfExists('sms_providers');
    }
}
