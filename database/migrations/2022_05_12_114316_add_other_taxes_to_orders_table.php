<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddOtherTaxesToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('total_other_taxes')->nullable()->comment('Other taxes like tax on fixed fee, service fee, container changes, delivery fee etc.');
            DB::statement("ALTER TABLE `orders` MODIFY `type` tinyint UNSIGNED NULL default(0) comment '0=none, 1=cab book for friend'");
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_other_taxes');
        });
    }
}
