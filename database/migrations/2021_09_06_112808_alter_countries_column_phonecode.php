<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCountriesColumnPhonecode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('nicename', 56)->after('name')->nullable();
			$table->string('iso3', 5)->after('nicename')->nullable();
			$table->integer('numcode')->after('iso3')->nullable();
			$table->integer('phonecode')->after('numcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('nicename');
            $table->dropColumn('iso3');
            $table->dropColumn('numcode');
            $table->dropColumn('phonecode');
        });
    }
}
