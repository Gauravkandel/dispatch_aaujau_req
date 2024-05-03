<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubClientPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_client_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sub_client_id')->unsigned()->nullable()->index();
            $table->decimal('dr',6,2)->nullable();
            $table->decimal('cr',6,2)->nullable();
            $table->timestamps();
        });

        Schema::table('sub_client_payments', function (Blueprint $table) {
			$table->foreign('sub_client_id')->references('id')->on('sub_clients')->onUpdate('cascade')->onDelete('set null');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_client_payments');
    }
}
