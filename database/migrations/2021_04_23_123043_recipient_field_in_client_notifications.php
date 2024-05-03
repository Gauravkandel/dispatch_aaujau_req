<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecipientFieldInClientNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_notifications', function (Blueprint $table) {
            $table->tinyInteger('recipient_request_recieved_sms')->default(0)->comment('1 for yes, 0 for no');
			$table->tinyInteger('recipient_request_received_email')->default(0)->comment('1 for yes, 0 for no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_notifications', function (Blueprint $table) {
            $table->dropColumn('recipient_request_recieved_sms');
            $table->dropColumn('recipient_request_received_email');
        });
    }
}
