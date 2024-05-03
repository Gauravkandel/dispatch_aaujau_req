<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_options', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('path');
            $table->string('title');
            $table->json('credentials')->nullable()->comment('credentials in json format');
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active, 2 delete');
            $table->unsignedTinyInteger('off_site')->nullable()->default(0)->comment('0 = on-site, 1 = off-site');
            $table->unsignedTinyInteger('test_mode')->default(0)->comment('0 = false, 1 = true');
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
        Schema::dropIfExists('payout_options');
    }
}
