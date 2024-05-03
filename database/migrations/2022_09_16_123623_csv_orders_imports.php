<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CsvOrdersImports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_orders_imports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->bigInteger('uploaded_by')->unsigned()->nullable();
            $table->tinyInteger('status')->nullable()->comment('1-Pending, 2-Success, 3-Failed, 4-In-progress');
            $table->longText('error')->nullable();
            $table->json('raw_data')->nullable();
            $table->tinyInteger('type')->nullable()->comment('0 for csv, 1 for woocommerce')->default(0);
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
        //
    }
}
