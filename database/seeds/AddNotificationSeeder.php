<?php

use Illuminate\Database\Seeder;
use App\Model\NotificationType;
use App\Model\NotificationEvent;
class AddNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notification_types_d = NotificationType::create([
            'name' => 'Customer Delivery OTP'
        ]);

        $notification_types_d->notification_events()->createMany([
            ['name' => 'Delivery OTP','message'=>'We have delivered your order number "order_number" please provide your OTP "deliver_otp" to your '.getAgentNomenclature().' to order delivery'],
        ]);
    }
}
