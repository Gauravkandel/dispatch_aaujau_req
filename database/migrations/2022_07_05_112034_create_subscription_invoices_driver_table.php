<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionInvoicesDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_invoices_driver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('subscription_id');
            $table->mediumText('slug')->nullable();
            $table->unsignedTinyInteger('payment_option_id')->nullable();
            $table->unsignedTinyInteger('status_id')->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('wallet_transaction_id')->nullable();
            $table->string('frequency');
            $table->string('driver_type')->nullable();
            $table->smallInteger('driver_commission_percentage')->nullable()->default(0);
            $table->unsignedDecimal('driver_commission_fixed', 16, 8)->nullable()->default(0);
            $table->text('transaction_reference')->nullable();
            $table->date('start_date')->nullable();
            $table->date('next_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('subscription_amount', 16, 8)->nullable();
            $table->decimal('discount_amount', 16, 8)->nullable();
            $table->decimal('paid_amount', 16, 8)->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->timestamps();

            $table->index('driver_id');
            $table->index('status_id');
            $table->index('subscription_id');
            $table->index('payment_option_id');
            $table->index('wallet_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_invoices_driver');
    }
}
