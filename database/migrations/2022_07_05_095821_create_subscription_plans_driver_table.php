<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans_driver', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('slug')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedDecimal('price', 16, 8)->default(0);
            $table->string('driver_type', 20)->nullable();
            $table->unsignedInteger('period')->default(0)->comment('plan validity in days');
            $table->string('frequency');
            $table->smallInteger('driver_commission_percentage')->nullable()->default(0);
			$table->unsignedDecimal('driver_commission_fixed', 16, 8)->nullable()->default(0);
            $table->smallInteger('sort_order')->default('1')->comment('for same position, display asc order');
            $table->enum('status',[0, 1])->default(1)->comment('0=Inactive, 1=Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plans_driver');
    }
}
